import Alpine from 'alpinejs';

// Live search with debounce against a JSON endpoint returning an array.
Alpine.data('remoteSearch', (url, { minLength = 2, delay = 500 } = {}) => ({
    query: '',
    results: [],
    open: false,
    loading: false,
    timer: null,

    input() {
        clearTimeout(this.timer);

        if (this.query.trim().length < minLength) {
            this.results = [];
            this.open = false;
            return;
        }

        this.timer = setTimeout(() => this.fetch(), delay);
    },

    async fetch() {
        this.loading = true;

        try {
            const response = await fetch(`${url}?q=${encodeURIComponent(this.query)}`, {
                headers: { Accept: 'application/json' },
            });
            this.results = await response.json();
            this.open = true;
        } finally {
            this.loading = false;
        }
    },

    close() {
        this.open = false;
    },
}));

// New-contract form: client selection (search or inline creation) plus a
// live preview of the monthly payment and due date, mirroring the legacy
// formulas (floor(amount * rate / 100); start + term months).
Alpine.data('contractForm', ({ quickStoreUrl, client = null, term, rate }) => ({
    client,
    amount: '',
    term,
    rate,
    creatingClient: false,
    clientError: '',

    get monthlyPayment() {
        const amount = parseInt(this.amount, 10);
        if (!amount || !this.rate) return null;
        return Math.floor(amount * (this.rate / 100));
    },

    get dueDate() {
        const months = parseInt(this.term, 10);
        if (!months) return null;
        const date = new Date();
        date.setMonth(date.getMonth() + months);
        return date.toLocaleDateString('es-CO', { day: 'numeric', month: 'long', year: 'numeric' });
    },

    // Printed buy-back (retroventa) price: amount + amount × rate% × term,
    // NOT floored per month — mirrors Contract::buyBackPrice().
    get buyBack() {
        const amount = parseInt(this.amount, 10);
        const months = parseInt(this.term, 10);
        if (!amount || !this.rate || !months) return null;
        return Math.round(amount + amount * (this.rate / 100) * months);
    },

    formatMoney(value) {
        return '$ ' + new Intl.NumberFormat('es-CO').format(value);
    },

    selectClient(client) {
        this.client = client;
        this.creatingClient = false;
    },

    async submitNewClient(form) {
        this.clientError = '';

        const response = await fetch(quickStoreUrl, {
            method: 'POST',
            headers: { Accept: 'application/json' },
            body: new FormData(form),
        });

        const data = await response.json();

        if (!response.ok) {
            this.clientError = Object.values(data.errors ?? {}).flat().join(' ')
                || 'No se pudo guardar el cliente.';
            return;
        }

        if (data.existed) {
            this.clientError = 'El cliente ya existía: se seleccionó el registro existente.';
        }

        this.selectClient(data);
        form.reset();
    },
}));

// Live preview for the extension (abono) form on the contract detail page:
// months bought = amount / monthly interest (unrounded, legacy rule) and the
// resulting due date = started_at + floor(term + extended + bought) months.
Alpine.data('abonoPreview', ({ monthly, startedAt, term, extended }) => ({
    amount: '',

    get monthsBought() {
        const amount = parseInt(this.amount, 10);
        if (!amount || monthly < 1) return null;
        return amount / monthly;
    },

    get monthsBoughtLabel() {
        const months = this.monthsBought;
        if (months === null) return null;
        return months.toLocaleString('es-CO', { maximumFractionDigits: 4 });
    },

    get newDueDate() {
        const months = this.monthsBought;
        if (months === null) return null;
        const date = new Date(`${startedAt}T00:00:00`);
        date.setMonth(date.getMonth() + Math.floor(term + extended + months));
        return date.toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric' });
    },
}));

// POS cart: mandatory client, searched items added as lines (one unit per
// line, no duplicates — legacy rule), per-line editable price.
Alpine.data('posCart', () => ({
    client: null,
    lines: [],
    warranty: 0,

    addItem(item) {
        if (this.lines.some((line) => line.id === item.id)) return;
        this.lines.push({ ...item, salePrice: item.price });
    },

    removeLine(index) {
        this.lines.splice(index, 1);
    },

    get total() {
        return this.lines.reduce((sum, line) => sum + (parseInt(line.salePrice, 10) || 0), 0);
    },

    formatMoney(value) {
        return '$ ' + new Intl.NumberFormat('es-CO').format(value);
    },
}));

window.Alpine = Alpine;

Alpine.start();
