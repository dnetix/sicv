# SICV — Business Logic Specification

The complete functional specification of the pawnshop (compraventa) system, as
extracted from the legacy CodeIgniter application (in production ~2001–2026)
and replicated in this Laravel app. If the application ever needs to be
understood, audited, or rebuilt again, this document is the source of truth.

Every rule marked **[pinned]** has an automated test that fails if the
behavior changes: `tests/Feature/Contracts/ContractMathTest.php`,
`ContractLifecycleTest.php`, `Reports/*`, `Store/*`, `Auth/*`, `Admin/*`.

---

## 1. The business

Compraventa El Diamante is a Colombian pawnshop. A client brings a valuable
item (gold, electronics, tools, …), signs a **buy-sale contract with a resale
pact** (*compraventa con pacto de retroventa*, Art. 1939 of the Colombian
Civil Code — legally a sale, economically a collateralized loan), and receives
cash. The client can:

1. **Keep the contract alive** by paying monthly interest ("abonos" /
   extensions), indefinitely.
2. **Redeem** the item ("el cliente cancela"): pay capital + outstanding
   interest and take the item back.
3. **Abandon it**: after the term (plus any paid extensions) lapses, the shop
   repossesses the item and sells it in its own store.

Side operations: a walk-in **store** that also sells directly-purchased goods
(POS with printed invoices, "notas de cobro"), an **expense** ledger, and a
set of management reports.

All money is **integer Colombian pesos** (no cents anywhere). All date logic
runs in **America/Bogota**.

### 1.1 Glossary (Spanish term → concept → schema)

| Spanish (legacy/UI) | Concept | Table / code |
|---|---|---|
| Contrato | Pawn contract (the loan) | `contracts` |
| Abono / Prórroga | Extension payment (interest buying months) | `contract_extensions` |
| Cancelación (cliente cancela) | Redemption — client pays and takes item back | status `Redeemed`, `settled_amount` |
| Anulación | Voiding a contract (mistake, legal problem) | `contract_voids`, status `Voided` |
| Vencido | Expired — past its term; computed, never stored | `Contract::expired()` scope |
| Pre-saca | Repossession queue (marked to pull) | `repossession_queue` |
| Saca / Sacar | The pull: foreclose queued contracts into the store | status `InStore` |
| Chatarrizar | Scrap/melt gold instead of selling it | status `Scrapped` |
| Artículo (almacén) | Store inventory item | `store_items` |
| Nota de cobro (NC) | Store sale invoice | `sales` + `sale_items` |
| Garantía | Warranty days on a sale | `sales.warranty_days` |
| Gasto | Business expense | `expenses` |
| Sello | Printed barcode tag attached to the pawned item | seals print view |
| Retroventa | Buy-back price printed on the legal contract | `Contract::buyBackPrice()` |

---

## 2. Domain model

```
clients 1──* contracts *──1 item_types          users stamp every operation
              │  │  │
              │  │  └──* contract_extensions
              │  ├── 0..1 contract_voids   (via contracts.void_id)
              │  ├── 0..1 repossession_queue
              │  └── 0..1 store_items ──* sale_items *──1 sales ──1 clients
expenses *──1 expense_types                company_settings (single row)
amount_overrides (polymorphic audit: contracts or sale_items)
```

**Numeric ids for `contracts`, `sales`, and `store_items` are business data**:
they are printed as Code128 barcodes on physical documents and tags in
circulation. They were carried over verbatim from the legacy database and must
never be renumbered. Clients are identified by their national id
(`document_number`, CC or CE), which is immutable after creation because it is
printed on legal contracts; internally a surrogate `id` is used.

### 2.1 Contract fields

| Column | Meaning |
|---|---|
| `client_id` | The pawning client ("EL VENDEDOR" on the legal document) |
| `description` | Free-text description of the pawned item(s) |
| `item_type_id` | Catalog type; **id 2 = Oro (gold) is special-cased** |
| `weight_grams` | Required for gold, otherwise optional |
| `amount` | The loan (and legal sale price), integer pesos |
| `monthly_rate` | Monthly interest percent (historically 5–10, default 10) |
| `term_months` | Agreed term (default 4) |
| `status` | See state machine below |
| `started_at` | Creation datetime — anchors ALL time math |
| `ended_at` | Datetime the contract left Active, whatever the exit path |
| `settled_amount` | Redemption: cash collected. Forfeit/scrap/sale: 0. Active/void: null |
| `void_id` | Set when voided |
| `user_id` | Operator who created it |

---

## 3. Contract state machine

Statuses (numeric values preserved from the legacy `estado` table —
`App\Enums\ContractStatus`):

| # | Enum | Spanish label | Meaning |
|---|---|---|---|
| 1 | Active | Activo | Live pawn |
| 2 | Redeemed | Cliente Cancela | Client paid and took the item back |
| 3 | InStore | Para Almacén | Foreclosed; item is store inventory |
| 4 | Sold | Vendido | The foreclosed item was sold in the store |
| 5 | Scrapped | Chatarrizado | Gold melted; never entered inventory |
| 6 | LegalHold | Problema Legal | Frozen by a legal issue (set manually) |
| 7 | Voided | Anulado | Annulled (mistake etc.) |

```
create ──► 1 Active ──┬─ redeem ───────────► 2 Redeemed   (terminal)
   │                  ├─ void ─────────────► 7 Voided     (terminal)
   │                  ├─ forfeit ──────────► 3 InStore ──sale──► 4 Sold (terminal)
   │                  └─ scrap (gold only) ► 5 Scrapped   (terminal)
   └─ extensions (0..n) only while Active; they never change the status
```

Rules **[pinned]**:

- Every transition out of Active requires the contract to currently BE
  Active; operations on any other status are rejected.
- **Expiry is never a stored state.** A contract stays Active forever unless
  an operator acts; "expired" is a computed predicate (§4.4).
- The **repossession queue** does not change the status. A queued contract is
  still Active, still accrues months, and can still receive extensions — but
  the single-contract operations (redeem / void / forfeit) are hidden in the
  UI until it is removed from the queue. Removing it re-enables them.
- Transitions stamp `ended_at = now()`. Exception: selling a foreclosed item
  keeps the `ended_at` stamped at forfeit time (only the status flips 3 → 4).
- Redeem stores the collected cash in `settled_amount`; forfeit, scrap and
  sale store `0`; void leaves it null.

---

## 4. Money and time formulas

These are the heart of the system. Two DIFFERENT month clocks exist **by
design** — the legacy app used one in PHP (contract page, payoff) and another
in SQL views (expiry, reports). Both are preserved. Code:
`app/Models/Contract.php`.

### 4.1 Monthly interest — `monthlyInterest()` [pinned]

```
monthlyInterest = floor(amount × monthly_rate / 100)      → integer pesos
```

E.g. $1.000.000 at 10% → $100.000/month; $999 at 5% → floor(49,95) = $49.

This **floored** value is used by: the contract detail page, extension-month
computation, and the payoff. The report screens instead use the **unfloored**
product `amount × rate / 100` (§4.6) — a legacy inconsistency kept on purpose.

### 4.2 Months elapsed, detail clock — `monthsElapsed()` [pinned]

Interest is charged **per started calendar month**, counted like this:

```
end   = today at MIDNIGHT        (if Active)
      = ended_at (with time)     (if closed)
diff  = calendar diff from started_at (with time) to end   → years, months
monthsElapsed = years×12 + months + 1
```

Consequences (all intentional, inherited from the legacy `month_diff() + 1`):

- A contract redeemed the **same day** it was created already owes 1 month.
- The month boundary is the same day-of-month **at midnight**: a contract
  started June 18 at 17:00 has NOT completed a month on July 18 (diff is 29
  days + hours → 1 month charged), but one started June 18 at 00:00 has
  (2 months charged). Time-of-day of creation matters.
- The printed legal term says `term_months × 30 días`, but the charging clock
  is calendar months — the two disagree by design; the calendar clock wins.

### 4.3 Extension payments (abonos) [pinned]

A payment of `P` pesos buys months of validity:

```
monthsBought = P / monthlyInterest        → float, NOT rounded
```

$50.000 on a $1.000.000/10% contract buys exactly 0,5 months. Fractional
months accumulate in `contract_extensions.months` (DECIMAL(8,4)) and:

- **Count in full** for the payoff and the expiry predicate (unfloored sum).
- **Are floored** only when computing the due date (§4.5).

Validation: contract must be Active; amount must be > 0; and
`monthlyInterest` must be ≥ 1 (the legacy app divided by zero on tiny
contracts — now rejected with a clear message). Records: amount, months,
`paid_at = now()` (date+time), operator.

### 4.4 Expiry predicate — `Contract::expired()` scope [pinned]

The **view clock** (from the legacy SQL view `tiempocontrato`), which has
**no +1**:

```
viewMonthsElapsed = TIMESTAMPDIFF(MONTH, started_at, CURDATE())   -- whole calendar months
expired ⇔ status = Active AND viewMonthsElapsed > term_months + SUM(extensions.months)
```

Strictly greater — a 4-month contract at exactly 4 elapsed months is NOT
expired. Verified 49/49 identical to the legacy `contratosvencidos` view on
production data.

### 4.5 Due date — `dueDate()` [pinned]

```
dueDate = started_at + floor(term_months + SUM(extensions.months)) months
```

- The fractional part of extension credit only moves the date once it
  accumulates to a whole month.
- Month addition overflows like PHP `strtotime('+N months')`: Jan 31 + 1
  month = Mar 3 (non-leap year). Carbon `addMonths` matches this.

### 4.6 Redemption payoff — `payoffAmount()` [pinned]

The suggested amount to collect when the client redeems:

```
payoff = (monthsElapsed − monthsExtended) × monthlyInterest + amount
```

using the **detail clock** (§4.2, with +1) and the **unrounded** extension
sum. The operator may charge a different figure (negotiations happen at the
counter); the entered value is saved verbatim in `settled_amount` and the
difference is audited (§8).

Report screens ("Faltante" / "A pagar" columns) show the same concept but
computed with the **view clock** and the **unfloored** monthly charge:

```
reportOwed  = (viewMonthsElapsed − monthsExtended) × (amount × rate / 100)
reportPayoff = amount + reportOwed
```

### 4.7 Buy-back price (retroventa) — `buyBackPrice()` [pinned]

Printed once on the legal contract at creation; prórrogas play no part:

```
retroventa = amount + amount × (rate / 100) × term_months     → NOT floored
```

### 4.8 Defaults

New contracts default to **4 months at 10%/month**, both editable per
contract. Historical data contains rates 5, 6.5, 8 and 10.

---

## 5. Contract operations

All lifecycle mutations live in single-purpose action classes under
`app/Actions/Contracts/`, each wrapped in a DB transaction (the legacy app
had none — a conscious fix).

### 5.1 Create (`CreateContract` flow, `ContractController@store`)

Inputs: client (searched or created inline — §9.2), description, item type,
weight (server-required iff type is gold), amount ≥ 1, rate 0–100, term 1–24.
Sets status Active, `started_at = now()`, stamping operator. On success the
browser is redirected straight to the printable legal contract (§10.1).

### 5.2 Extension (`ExtendContract`) — §4.3.

### 5.3 Redemption (`RedeemContract`)

Only Active. Stores the entered amount as `settled_amount` (0 allowed),
status → Redeemed, `ended_at = now()`. Difference vs `payoffAmount()`
audited.

### 5.4 Void (`VoidContract`)

Only Active. Requires a reason (min 3 chars). Creates a `contract_voids` row
(reason, **original_amount**, date, operator), then sets status → Voided,
`ended_at = now()` and **zeroes `contracts.amount`** so voided contracts do
not pollute any money report. The legacy app destroyed the original amount
(it only survived inside the reason text); it is now a real column.

### 5.5 Forfeit — single (`ForfeitContract`)

Only Active. From the contract page ("Mover al almacén"):

1. Creates a `store_items` row: `contract_id`, description and item type
   copied from the contract, `entered_at = today`, **`cost = amount`** (the
   loan is the shop's cost basis), `price` = entered asking price, `stock=1`.
2. Contract: status → InStore, `ended_at = now()`, `settled_amount = 0`.
3. Removes any repossession-queue row.

Suggested price on this screen = the **payoff** (§4.6); entered differences
are audited against it.

> Legacy note: the old app stored `cost = 0` and substituted the loan amount
> at query time via SQL UNIONs. The import canonicalized cost = loan amount;
> this app always stores the real cost.

### 5.6 Repossession queue + bulk pull (`QueueContracts`, `PullQueuedContracts`, `ScrapContract`)

The periodic housekeeping flow for abandoned contracts:

1. **Expired report** lists Active + expired + not-queued contracts with the
   owed/payoff math (§4.6 report variant), most months-behind first.
   Operators tick contracts → **queue** them (pre-saca). Already-queued ids
   are silently skipped (the legacy app aborted the whole batch on one
   duplicate).
2. **Queued report** lists Active + expired + queued contracts, each with an
   editable sale price **defaulting to the loan `amount`** (note: NOT the
   payoff — this differs from the single forfeit on purpose, matching legacy)
   and, if any gold is present, a global choice: *Chatarrizar* vs *Mover al
   almacén*.
3. **Pull** ("sacar"), per selected contract:
   - Gold + scrap chosen → `ScrapContract`: status → Scrapped, no inventory
     row ever created (the gold is melted).
   - Otherwise → `ForfeitContract` at the entered price, audited against the
     suggested loan amount.
   - Queue rows are removed on both paths.
4. **Unqueue** removes selected contracts from the queue without any status
   change (contracts return to the expired report and regain their
   single-contract operations).

---

## 6. Store and POS

### 6.1 Inventory (`store_items`)

Two entry paths:

- **Foreclosure** (§5.5/5.6): `contract_id` set (unique — one item per
  contract), stock 1, cost = loan amount.
- **Direct purchase** ("Nuevo artículo"): no contract, operator enters
  description, type, cost, asking price, and stock (≥ 1 — the only path to
  multi-unit stock). The legacy form silently defaulted an empty cost to 10;
  cost is now required.

"Available for sale" ⇔ `stock > 0` (`StoreItem::available()` scope).

### 6.2 POS search [pinned]

Query split on whitespace; **every token must match**; over available items
only, ordered by id, limit 30:

- any token: `description LIKE %token%`
- numeric token additionally: `id LIKE token%` OR `contract_id LIKE token%`
  (prefix match, so typing a barcode number finds the item)

### 6.3 Checkout (`CreateSale`) [pinned]

- A registered **client is mandatory** (invoices are nominative legal
  documents).
- The cart holds **one unit per line**; the same item cannot appear twice
  (server-enforced; legacy only checked in JS). Quantity is always 1 —
  legacy behavior kept.
- Each line's price defaults to the item's asking price and is
  **operator-editable**; the entered price is what's stored in
  `sale_items.price` and summed into `sales.total`; differences vs the
  asking price are audited per line.
- Warranty days: one integer for the whole invoice, default 0, max 365.
  Drives the legal wording on the receipt (§10.3).
- Transactionally (row-locked): validates every line has stock ≥ 1 (any
  failure rejects the whole sale — legacy silently skipped bad lines),
  creates `sales` + `sale_items`, decrements stock by 1 per line, and for
  items sourced from a contract flips that contract to **Sold** (keeping its
  original `ended_at` if already stamped, else stamping now with
  `settled_amount = 0` — the legacy `setVendido` semantics).
- Redirects to the printable receipt.

### 6.4 Expenses

Form: amount ≥ 1, type (catalog `expense_types`), free-text description.
`spent_at` is **always the server's now()** (the date shown in the form is
informative only — legacy behavior). The entry screen lists the current
month-to-date expenses with a total.

---

## 7. Reports

All reports require login (the legacy app left the financial ones public —
fixed). Date filters default to **1st of current month → today**, except the
financial report which defaults to **today only**. Shared date/type filter
component; all money via the same formulas of §4.6 (report variant).

| Report | Contents & math |
|---|---|
| **Contratos Vencidos** | Active ∧ expired ∧ not queued, ordered by months-behind desc. Columns: view months elapsed, months extended, unfloored monthly charge, Faltante (owed), A pagar (payoff). Checkboxes feed the queue. |
| **Pre-Saca** | Active ∧ expired ∧ queued; per-row editable sale price (default = loan); gold scrap choice; pull/unqueue actions. |
| **Contratos Activos** | Always shows the global headline (count + total lent over ALL active contracts). The filtered list (by creation date range and item type) appears only after filtering, with the same owed/payoff columns and totals. |
| **Abonos** | Extensions in range (by `paid_at`) joined to contract + client; total collected. |
| **Reporte Financiero** | Cash movement for the range. **Out**: new loans (`contracts.amount` by `started_at`) + expenses + direct-purchase costs (contract-sourced items excluded — their cost IS the loan, already counted). **In**: extension payments + store sales (`sales.total`) + redemption collections (`settled_amount` of contracts redeemed in range). Shows redemption capital and *utilidad* = collected − capital, plus the period balance. |
| **Reporte Gastos** | Expenses by range and type; total. |
| **Artículos Vendidos** | Sale lines by sale date and item type; NC link, contract link, quantity and price totals. |
| **Contratos Sacados** | Contracts with status ∈ {InStore, Sold, Scrapped}, filtered by `ended_at` range; per-row `TIMESTAMPDIFF(MONTH, started_at, ended_at) + 1` months run and extension totals; totals row. |
| **Contratos Cancelados** | Same shape, status = Redeemed. |
| **Estadísticas** | Per-calendar-month contract count and total lent for a chosen year; SVG bar chart + table. (Dec 31 datetimes are included — a legacy off-by-one fixed.) |
| **Artículos a la venta** | Current inventory (stock > 0) with cost/price/stock totals — the store index page. |

---

## 8. Amount-override auditing

Three money inputs are **suggestions the operator may change** (counter
negotiations are part of the business): the redemption payoff, the forfeit
asking price, and each POS line price. The operation is never blocked, but
whenever `entered ≠ computed` a row is written to `amount_overrides`:

| Column | Value |
|---|---|
| `operation` | `redeem` \| `forfeit` \| `sale_line` |
| `auditable` | polymorphic: the `Contract` or the `SaleItem` |
| `computed_amount` | what the system suggested **in that context** (payoff for redeem/single forfeit; loan amount for the bulk pull; asking price for a sale line) |
| `entered_amount` | what was actually used |
| `user_id`, `created_at` | who and when |

Administrators review them at `/admin/overrides` (date/operation filters,
signed difference, links to the audited contract/invoice). Exact matches are
NOT recorded — the screen only contains true deviations.

---

## 9. Clients, users, auth

### 9.1 Clients

- Identified by national id: `document_type` ∈ {CC, CE} + `document_number`
  (min 3 chars, **no dots**, unique, **immutable** — it appears on printed
  legal contracts). Everything else is editable.
- Search [pinned]: tokens AND-ed; each token matches `document_number`
  **prefix** OR `name` substring; limit 15.
- City / issue-place autocomplete is sourced from `document_issue_place`
  values already stored on other clients (self-feeding, as in legacy).
- The client page shows the full contract history (with per-contract
  extension totals and status, including the settled amount on redemptions)
  and store purchase history.

### 9.2 Inline creation from the new-contract screen

If the entered document already exists, **the existing client wins**: the
posted data is discarded, the existing record is selected, and the operator
is told. (Legacy behavior; prevents accidental duplicates at the counter.)

### 9.3 Users and roles

`users.role` is a numeric ladder preserved from legacy
(`App\Enums\UserRole`): **3 Empleado, 4 Empleado+, 6 Administrador**.
Authorization is a threshold check (`role >= level`); in practice only two
levels matter: any authenticated active user (≥3) operates everything, and
**Administrator (≥6)** additionally manages company settings, users, and the
override review. Middleware: `role:Administrator`.

Login is by **username** (not email); inactive users cannot log in. Passwords
are bcrypt; users imported from the legacy system carry their old
`haval128,4` hash in `legacy_password_hash` and are **upgraded to bcrypt
transparently on their first successful login** [pinned] — nobody's password
changed at migration.

Quick search (header box, matching the barcode prefixes on printed
documents): `NC{n}` → invoice, `CL{document}` → client, bare number →
contract, anything else → client search.

---

## 10. Printed documents

Printing is browser-native (`window.print()`); print CSS in
`resources/css/print.css`. Barcodes are rendered as **font glyphs**: the
bundled `Code 128` webfont plus `App\Support\Code128::encode()`, a direct
port of the legacy generator — Code128 set B: START-B glyph (char 204), the
literal payload characters, a mod-103 checksum glyph (+32, or +100 when the
checksum exceeds 94), STOP glyph (char 206), re-encoded Latin-1 → UTF-8.
Because both algorithm and font are identical to the legacy app, **new
printouts scan the same as the thousands of tags already in circulation**.

### 10.1 Legal contract (`contracts/{id}/print`)

Header (logo or company name; the Art. 1939 line; address/city/phone),
barcode of the bare contract id, contract number, contract and due dates,
the parties paragraph (client = EL VENDEDOR with full identity; company = EL
COMPRADOR with razón social and NIT), item description, price, the fixed
legal clauses — including the **retroventa price** (§4.7) and the term as
`term_months × 30 días` — the date in words, and signature blocks with a
fingerprint box. Reprints from the contract page always carry a **DUPLICADO**
watermark; the print right after creation is the original. Any status can be
reprinted.

### 10.2 Seals (`/seals`)

One tag per contract created in a date range (default today): barcode +
contract id + start datetime + first 100 chars of the description + amount.
These are physically attached to the stored items.

### 10.3 Sale receipt (`sales/{id}/print`)

Header, barcode of **`NC` + invoice id**, buyer identification paragraph,
line table (item id, description, qty, price) with total, the fixed consumer-
law text (no right of retraction — Art. 47, Ley 1480 de 2011), and the
warranty clause: `warranty_days = 0` → "NO tiene garantía… ha expirado el
término de garantía legal"; otherwise → "garantía de N días" with the misuse
exclusion. Signature blocks.

---

## 11. Legacy data migration (context)

`php artisan legacy:import --force` reloads everything from the legacy
`sicv-ci` database (read-only connection). Key decisions baked into it:

- Ids preserved for contracts, extensions, sales, store items, expenses,
  voids, catalogs. Clients get surrogate ids; FKs remapped via document
  number.
- Legacy MySQL matched usernames case-insensitively; the import does too.
- Voided amounts recovered from the `[Valor Anterior $ N]` text the legacy
  app appended to the reason.
- Foreclosed items' `cost` canonicalized to the contract amount (legacy
  stored 0 and substituted at read time).
- ~300 invoices with NULL stored totals get their total derived from line
  sums (that's what the legacy screens displayed anyway).
- Repossession-queue rows whose contract is no longer Active are dropped
  (legacy never cleaned them).
- utf8mb3 → utf8mb4; company logo file copied from the legacy assets.
- Ends with a ~23-row verification table (counts, per-status counts, amount
  sums vs legacy) that must be all OK; `parity-check.php` additionally
  compares financial-report components for three date ranges.

## 12. Intentional differences from the legacy app

Preserved quirks are described inline above; these are the deliberate
CHANGES (agreed during the rewrite — see also `docs/CUTOVER.md`):

1. Every route requires authentication; admin areas require role ≥ 6.
2. Self-updater (remote zip + arbitrary PHP execution) and the gold-price
   screen-scraper were dropped; `ingreso`/`tipoingreso` tables (dead code)
   were not migrated.
3. Multi-write operations are transactional; POS rejects duplicate or
   out-of-stock lines instead of skipping them silently; queueing skips
   duplicates instead of aborting the batch; the `=`/`==` bug that made any
   unknown bulk operation fall into "unqueue" is structurally gone (separate
   routes).
4. Voided contracts keep their original amount in a real column.
5. Server-side validation exists everywhere (gold weight, amounts, division-
   by-zero guard on tiny contracts, client existence).
6. Amount overrides are audited (§8) — the legacy app accepted any posted
   amount with no trace.
7. Small report fixes: sold-items default date filter works; yearly stats
   include Dec 31; closed-contract reports default to month-to-date instead
   of rendering empty; queue rows are cleaned on every exit path.
8. User management has a UI (legacy users were created directly in the DB).

## 13. Numeric fidelity notes

- Extension months: legacy stored 32-bit FLOAT; this app stores
  DECIMAL(8,4). Aggregates over months can differ from the legacy app by
  well under one peso (measured: $0,52 across $27,6M of expired payoffs).
- Interest/payoff arithmetic is PHP float, as in legacy; all displayed money
  is formatted to whole pesos (`money()` helper: `$ 1.234.567`).
- The `TIMESTAMPDIFF` expiry predicate runs in SQL against `CURDATE()` — the
  DB server must share the app timezone (America/Bogota) for midnight
  boundaries to agree.
