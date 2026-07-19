<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportLegacyData extends Command
{
    protected $signature = 'legacy:import
        {--force : Run without asking for confirmation}';

    protected $description = 'Import all data from the legacy CodeIgniter database (sicv-ci), replacing current data';

    private const int INSERT_CHUNK = 1000;

    /**
     * username => id map built while importing users.
     *
     * @var array<string, int>
     */
    private array $userIds = [];

    /**
     * client document number => id map built while importing clients.
     *
     * @var array<string, int>
     */
    private array $clientIds = [];

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm(
            'This will REPLACE all data in the current database with the legacy data. Continue?'
        )) {
            return self::FAILURE;
        }

        $start = microtime(true);

        // No transaction: TRUNCATE implicitly commits in MySQL. The command
        // is idempotent — a failed run is simply re-run from scratch.
        $this->truncateTargetTables();

        $this->components->task('Users', fn () => $this->importUsers());
        $this->components->task('Item types', fn () => $this->importItemTypes());
        $this->components->task('Expense types', fn () => $this->importExpenseTypes());
        $this->components->task('Company settings', fn () => $this->importCompanySettings());
        $this->components->task('Clients', fn () => $this->importClients());
        $this->components->task('Contract voids', fn () => $this->importContractVoids());
        $this->components->task('Contracts', fn () => $this->importContracts());
        $this->components->task('Contract extensions', fn () => $this->importContractExtensions());
        $this->components->task('Repossession queue', fn () => $this->importRepossessionQueue());
        $this->components->task('Store items', fn () => $this->importStoreItems());
        $this->components->task('Sales', fn () => $this->importSales());
        $this->components->task('Sale items', fn () => $this->importSaleItems());
        $this->components->task('Expenses', fn () => $this->importExpenses());

        $this->newLine();
        $this->components->info(sprintf('Import finished in %.1fs', microtime(true) - $start));

        return $this->verify();
    }

    private function legacy(): Connection
    {
        return DB::connection('legacy');
    }

    private function truncateTargetTables(): void
    {
        $tables = [
            'amount_overrides', 'expenses', 'sale_items', 'sales', 'store_items',
            'repossession_queue', 'contract_extensions', 'contracts', 'contract_voids',
            'clients', 'company_settings', 'expense_types', 'item_types', 'users',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function importUsers(): void
    {
        $rows = $this->legacy()->table('usuario')->get()->map(fn ($user) => [
            'username' => trim($user->idusuario),
            'name' => trim($user->nombre),
            'email' => $this->nullable($user->email),
            'phone' => $this->nullable($user->telefono),
            'role' => (int) $user->rol,
            'active' => (bool) $user->activo,
            'password' => null,
            'legacy_password_hash' => $user->password,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->insert('users', $rows);

        // Legacy MySQL matched usernames case-insensitively (e.g. rows
        // stamped "Carlos" against user "carlos"); mirror that in the map.
        $this->userIds = DB::table('users')
            ->pluck('id', 'username')
            ->mapWithKeys(fn ($id, $username) => [mb_strtolower($username) => $id])
            ->all();
    }

    private function importItemTypes(): void
    {
        $this->insert('item_types', $this->legacy()->table('tipoarticulo')->get()->map(fn ($type) => [
            'id' => $type->idtipoarticulo,
            'name' => trim($type->tipoarticulo ?? 'Sin definir'),
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    private function importExpenseTypes(): void
    {
        $this->insert('expense_types', $this->legacy()->table('tipogasto')->get()->map(fn ($type) => [
            'id' => $type->idtipogasto,
            'name' => trim($type->descripcion),
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    private function importCompanySettings(): void
    {
        $config = $this->legacy()->table('config')->where('idconfig', 1)->first();

        if ($config === null) {
            return;
        }

        DB::table('company_settings')->insert([
            'legal_name' => $config->razonsocial,
            'tax_id' => $config->nit,
            'name' => $config->nombre,
            'address' => $config->direccion,
            'phone' => $config->telefono,
            'city' => $config->ciudad,
            'logo_path' => $this->importLogo($this->nullable($config->logo)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Copy the company logo from the legacy assets folder (mounted read-only
     * in development, or pointed at the old app's assets/img on the server).
     */
    private function importLogo(?string $filename): ?string
    {
        if ($filename === null) {
            return null;
        }

        $source = rtrim(env('LEGACY_ASSETS_PATH', '/var/www/legacy-assets'), '/').'/img/'.$filename;

        if (! is_readable($source)) {
            $this->components->warn("Company logo [$source] not found; skipping copy.");

            return null;
        }

        $path = 'company/'.$filename;
        Storage::disk('local')->put($path, (string) file_get_contents($source));

        return $path;
    }

    private function importClients(): void
    {
        $rows = $this->legacy()->table('cliente')->orderBy('idcliente')->get()->map(fn ($client) => [
            'document_number' => trim($client->idcliente),
            'document_type' => trim($client->tipoid),
            'name' => trim($client->nombre),
            'document_issue_place' => $this->nullable($client->lugarexpedicion),
            'address' => $this->nullable($client->direccion),
            'phone' => $this->nullable($client->telefono),
            'mobile' => $this->nullable($client->celular),
            'email' => $this->nullable($client->email),
            'city' => $this->nullable($client->ciudad),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->insert('clients', $rows);

        $this->clientIds = DB::table('clients')->pluck('id', 'document_number')->all();
    }

    private function importContractVoids(): void
    {
        $this->insert('contract_voids', $this->legacy()->table('anulacion')->orderBy('idanulacion')->get()->map(fn ($void) => [
            'id' => $void->idanulacion,
            'reason' => trim($void->motivo),
            'original_amount' => $this->extractVoidedAmount($void->motivo),
            'voided_at' => $void->fecha,
            'user_id' => $this->userId($void->usuario),
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    /**
     * The legacy app appended "[Valor Anterior $ 1.234.567]" to the void
     * reason and zeroed the contract amount; recover it into a real column.
     */
    private function extractVoidedAmount(string $reason): ?int
    {
        if (preg_match('/\[Valor Anterior \$\s*([\d.,]+)\]/', $reason, $matches) === 1) {
            return (int) str_replace(['.', ','], '', $matches[1]);
        }

        return null;
    }

    private function importContracts(): void
    {
        $this->legacy()->table('contrato')->orderBy('idcontrato')
            ->chunkById(self::INSERT_CHUNK, function (Collection $contracts) {
                $this->insert('contracts', $contracts->map(fn ($contract) => [
                    'id' => $contract->idcontrato,
                    'client_id' => $this->clientId($contract->cliente),
                    'description' => trim($contract->articulo),
                    'item_type_id' => $contract->tipoarticulo,
                    'weight_grams' => $contract->peso,
                    'amount' => $contract->valor,
                    'monthly_rate' => $contract->porcentaje,
                    'term_months' => $contract->nromeses,
                    'status' => $contract->estado,
                    'started_at' => $contract->fechaingreso,
                    'ended_at' => $contract->fechasalida,
                    'settled_amount' => $contract->valorcancelado,
                    'void_id' => $contract->anulacion,
                    'user_id' => $this->userId($contract->usuario),
                    'created_at' => $contract->fechaingreso,
                    'updated_at' => $contract->fechasalida ?? $contract->fechaingreso,
                ]));
            }, 'idcontrato');
    }

    private function importContractExtensions(): void
    {
        $this->legacy()->table('prorroga')->orderBy('idprorroga')
            ->chunkById(self::INSERT_CHUNK, function (Collection $extensions) {
                $this->insert('contract_extensions', $extensions->map(fn ($extension) => [
                    'id' => $extension->idprorroga,
                    'contract_id' => $extension->contrato,
                    'amount' => $extension->valor,
                    'months' => $extension->nromeses,
                    'paid_at' => $extension->fecha.' '.$extension->hora,
                    'user_id' => $this->userId($extension->usuario),
                    'created_at' => $extension->fecha.' '.$extension->hora,
                    'updated_at' => $extension->fecha.' '.$extension->hora,
                ]));
            }, 'idprorroga');
    }

    private function importRepossessionQueue(): void
    {
        // Only contracts still active belong in the queue: the legacy app
        // never cleaned rows for contracts already pulled or redeemed.
        $rows = $this->legacy()->table('presaca')
            ->join('contrato', 'contrato.idcontrato', '=', 'presaca.contrato')
            ->where('contrato.estado', 1)
            ->select('presaca.*')
            ->get()
            ->map(fn ($entry) => [
                'contract_id' => $entry->contrato,
                'queued_at' => $entry->fecha.' 00:00:00',
                'user_id' => $this->userId($entry->usuario),
            ]);

        $this->insert('repossession_queue', $rows);
    }

    private function importStoreItems(): void
    {
        // For foreclosed items the legacy row stored cost 0 and substituted
        // the contract's loan amount at query time; store it for real.
        $this->legacy()->table('articulo')->orderBy('idarticulo')
            ->leftJoin('contrato', 'contrato.idcontrato', '=', 'articulo.contrato')
            ->select('articulo.*', 'contrato.valor AS contract_amount')
            ->chunkById(self::INSERT_CHUNK, function (Collection $items) {
                $this->insert('store_items', $items->map(fn ($item) => [
                    'id' => $item->idarticulo,
                    'contract_id' => $item->contrato,
                    'description' => trim($item->articulo),
                    'item_type_id' => $item->tipoarticulo,
                    'entered_at' => $item->fechaingreso,
                    'cost' => $item->contrato !== null ? $item->contract_amount : $item->valorcompra,
                    'price' => $item->valorventa,
                    'stock' => $item->disponible,
                    'created_at' => $item->fechaingreso,
                    'updated_at' => $item->fechaingreso,
                ]));
            }, 'articulo.idarticulo', 'idarticulo');
    }

    private function importSales(): void
    {
        // ~300 legacy invoices have a NULL stored total (a quirk of the old
        // insert code); the app always recomputed totals from the lines, so
        // derive those the same way.
        $this->legacy()->table('notacobro')->orderBy('idnotacobro')
            ->selectRaw('notacobro.*, (SELECT SUM(valor) FROM detalle WHERE detalle.notacobro = notacobro.idnotacobro) AS lines_total')
            ->chunkById(self::INSERT_CHUNK, function (Collection $sales) {
                $this->insert('sales', $sales->map(fn ($sale) => [
                    'id' => $sale->idnotacobro,
                    'client_id' => $this->clientId($sale->cliente),
                    'sold_at' => $sale->fecha,
                    'total' => $sale->total ?? $sale->lines_total ?? 0,
                    'warranty_days' => $sale->garantia ?? 0,
                    'user_id' => $this->userId($sale->usuario),
                    'created_at' => $sale->fecha,
                    'updated_at' => $sale->fecha,
                ]));
            }, 'idnotacobro');
    }

    private function importSaleItems(): void
    {
        $rows = $this->legacy()->table('detalle')->orderBy('notacobro')->get()->map(fn ($item) => [
            'sale_id' => $item->notacobro,
            'store_item_id' => $item->articulo,
            'price' => $item->valor,
            'quantity' => $item->cantidad,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->insert('sale_items', $rows);
    }

    private function importExpenses(): void
    {
        $this->legacy()->table('gasto')->orderBy('idgasto')
            ->chunkById(self::INSERT_CHUNK, function (Collection $expenses) {
                $this->insert('expenses', $expenses->map(fn ($expense) => [
                    'id' => $expense->idgasto,
                    'expense_type_id' => $expense->tipogasto,
                    'amount' => $expense->valor,
                    'description' => $this->nullable($expense->concepto),
                    'spent_at' => $expense->fecha,
                    'user_id' => $this->userId($expense->usuario),
                    'created_at' => $expense->fecha,
                    'updated_at' => $expense->fecha,
                ]));
            }, 'idgasto');
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     */
    private function insert(string $table, Collection $rows): void
    {
        foreach ($rows->chunk(self::INSERT_CHUNK) as $chunk) {
            DB::table($table)->insert($chunk->values()->all());
        }
    }

    private function userId(string $username): int
    {
        return $this->userIds[mb_strtolower(trim($username))]
            ?? throw new \RuntimeException("Unknown legacy user [$username]");
    }

    private function clientId(string $documentNumber): int
    {
        return $this->clientIds[trim($documentNumber)]
            ?? throw new \RuntimeException("Unknown legacy client [$documentNumber]");
    }

    private function nullable(?string $value): ?string
    {
        $value = trim($value ?? '');

        return $value === '' ? null : $value;
    }

    private function verify(): int
    {
        $legacy = $this->legacy();

        $checks = [
            ['users', $legacy->table('usuario')->count(), DB::table('users')->count()],
            ['clients', $legacy->table('cliente')->count(), DB::table('clients')->count()],
            ['contracts', $legacy->table('contrato')->count(), DB::table('contracts')->count()],
            ['contracts amount sum', (int) $legacy->table('contrato')->sum('valor'), (int) DB::table('contracts')->sum('amount')],
            ['contracts settled sum', (int) $legacy->table('contrato')->sum('valorcancelado'), (int) DB::table('contracts')->sum('settled_amount')],
            ['extensions', $legacy->table('prorroga')->count(), DB::table('contract_extensions')->count()],
            ['extensions amount sum', (int) $legacy->table('prorroga')->sum('valor'), (int) DB::table('contract_extensions')->sum('amount')],
            ['voids', $legacy->table('anulacion')->count(), DB::table('contract_voids')->count()],
            [
                'queue (active only)',
                $legacy->table('presaca')->join('contrato', 'contrato.idcontrato', '=', 'presaca.contrato')->where('estado', 1)->count(),
                DB::table('repossession_queue')->count(),
            ],
            ['store items', $legacy->table('articulo')->count(), DB::table('store_items')->count()],
            ['store stock sum', (int) $legacy->table('articulo')->sum('disponible'), (int) DB::table('store_items')->sum('stock')],
            ['sales', $legacy->table('notacobro')->count(), DB::table('sales')->count()],
            ['sale items', $legacy->table('detalle')->count(), DB::table('sale_items')->count()],
            ['sale items amount sum', (int) $legacy->table('detalle')->sum('valor'), (int) DB::table('sale_items')->sum('price')],
            ['expenses', $legacy->table('gasto')->count(), DB::table('expenses')->count()],
            ['expenses amount sum', (int) $legacy->table('gasto')->sum('valor'), (int) DB::table('expenses')->sum('amount')],
        ];

        foreach ($legacy->table('contrato')->selectRaw('estado, COUNT(*) AS total')->groupBy('estado')->pluck('total', 'estado') as $status => $count) {
            $checks[] = [
                "contracts status $status",
                $count,
                DB::table('contracts')->where('status', $status)->count(),
            ];
        }

        $failures = 0;

        $this->table(
            ['Metric', 'Legacy', 'Imported', 'Status'],
            array_map(function (array $check) use (&$failures) {
                [$metric, $expected, $actual] = $check;
                $ok = $expected === $actual;
                $failures += $ok ? 0 : 1;

                return [$metric, number_format($expected), number_format($actual), $ok ? 'OK' : 'MISMATCH'];
            }, $checks),
        );

        if ($failures > 0) {
            $this->components->error("$failures verification check(s) failed.");

            return self::FAILURE;
        }

        $this->components->info('All verification checks passed.');

        return self::SUCCESS;
    }
}
