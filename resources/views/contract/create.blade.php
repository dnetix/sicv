@extends('layouts.base')

@section('content')
    <form action="#" method="POST" x-data="app()">
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <div class="shadow">
                    <div class="px-4 py-4 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Nuevo contrato</h3>
                        <p class="mt-1 text-sm text-gray-500">Generar un nuevo contrato a cliente</p>
                    </div>

                    <div class="bg-white py-6 px-4 space-y-6">

                        <div class="col-span-3">
                            <label for="terms" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" name="terms" id="terms" x-model="terms" x-on:keyup.debounce="searchClient" placeholder="Buscar cliente" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="bg-white shadow overflow-hidden">

                            <template x-if="selectedClient ? true : false">
                                <div class="p-4 flex items-center bg-gray-50">
                                    <div class="min-w-0 flex-1 flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                                <i class="fa fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-auto">
                                            <p class="text-indigo-600" x-text="selectedClient.name">Nombre Cliente</p>
                                            <p class="text-sm text-gray-600" x-text="`${selectedClient.id_type} ${selectedClient.id_number}`">CC 1040035000</p>
                                            <p class="text-sm text-gray-600" x-text="selectedClient.id_expedition">Abejorral</p>
                                        </div>
                                        <div class="flex-auto">
                                            <p class="text-sm">
                                                <span class="w-5 inline-block text-center"><i class="text-yellow-500 fa fa-mobile-alt"></i></span>
                                                <span x-text="`${selectedClient.cell_number ?? ''} ${selectedClient.phone_number ?? ''}`">3006108300</span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="w-5 inline-block text-center"><i class="text-yellow-500 fa fa-map-marker-alt"></i></span>
                                                <span x-text="selectedClient.city">La Ceja</span>
                                            </p>
                                            <p class="text-sm">
                                                <span class="w-5 inline-block text-center"><i class="text-yellow-500 fa fa-envelope"></i></span>
                                                <span x-text="selectedClient.email ?? 'No aplica'">dnetix@gmail.com</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <ul role="list" class="divide-y divide-gray-200">

                                <template x-for="(client, index) in clients">
                                    <li>
                                        <a href="javascript:;" x-on:click="selectClient(index)" class="block hover:bg-gray-100">
                                            <div class="flex py-2 px-4 gap-4 items-center">
                                                <div class="flex items-center gap-4 flex-auto">
                                                    <div class="text-yellow-500 px-3">
                                                        <i class="fa fa-user-alt"></i>
                                                    </div>
                                                    <span class="w-3/6 text-gray-800" x-text="client.name">Diego Arturo Calle</span>
                                                    <span class="w-1/6 text-sm text-gray-700" x-text="`${client.id_type} ${client.id_number}`">CC 1040035072</span>
                                                    <span class="w-1/6 text-sm text-gray-700" x-text="`${client.cell_number ?? ''} ${client.phone_number ?? ''}`">3006108300</span>
                                                </div>
                                                <div class="text-gray-400">
                                                    <i class="fa fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </template>

                            </ul>
                        </div>


                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Artículo</label>
                                <textarea name="description" id="description" placeholder="Descripción del artículo" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>

                            <div class="col-span-3">
                                <label for="article_type_id" class="block text-sm font-medium text-gray-700">
                                    Tipo Artículo
                                </label>
                                <select id="article_type_id" name="article_type_id" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selecciona</option>
                                    @foreach($articleTypes as $articleType)
                                        <option value="{{ $articleType->id() }}">{{ $articleType->name() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-3">
                                <label for="weight" class="block text-sm font-medium text-gray-700">Peso</label>
                                <input type="number" name="weight" id="weight" placeholder="Gramos" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-3">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                                <input type="text" name="amount" id="amount" x-ref="moneyInput" x-on:focus="$refs.moneyInput.value = $refs.moneyInput.value.replace(',','');" x-on:blur="amount = $refs.moneyInput.value; $nextTick(() => {displayAmount = formatMoney(amount); $refs.moneyInput.value = displayAmount});" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-3">
                                <label for="extension" class="block text-sm font-medium text-gray-700">Prorroga</label>
                                <input type="text" name="extension" disabled id="extension" :value="formatMoney(amount * (percent / 100))" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-6 lg:col-span-2">
                                <label for="contract-start" class="block text-sm font-medium text-gray-700">Fecha
                                    Inicio</label>
                                <input type="text" name="contract-start" id="contract-start" disabled value="{{ \App\Helpers\Dates\DateHelper::create()->toSQLDate() }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-6 lg:col-span-2">
                                <label for="contract-end" class="block text-sm font-medium text-gray-700">Fecha
                                    finalización</label>
                                <input type="text" name="contract-end" id="contract-end" disabled x-model="endDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right">
                        <input type="hidden" x-model="selectedClient?.id" name="client_id" required>
                        <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar contrato
                        </button>
                    </div>
                </div>

            </div>

            <div class="w-1/4 p-2">

                <div class="w-full inline-block align-bottom rounded-lg text-left overflow-hidden">
                    <div class="px-4 py-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Tipo contrato</h3>
                    </div>
                    <div class="px-4 pt-2 pb-5 space-y-6">
                        <div class="">
                            <label for="state" class="block text-sm font-medium text-gray-700">Meses</label>
                            <input type="text" name="state" id="state" x-model="months" x-on:change="calculateEndDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="">
                            <label for="percent" class="block text-sm font-medium text-gray-700">Porcentaje
                                compra</label>
                            <input type="text" name="percent" id="percent" x-model="percent" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function app() {
            return {
                amount: 0,
                months: '4',
                displayAmount: '',
                percent: 10,
                formatMoney(number) {
                    return new Intl.NumberFormat('en-US').format(number);
                },
                terms: '',
                selectedClient: {!! $client ? json_encode($client, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) : 'null' !!},
                clients: [],
                endDate: '{{ \App\Helpers\Dates\DateHelper::create('+4 months')->toSQLDate() }}',
                searchClient() {
                    console.log(this.terms);
                    if (this.terms.length > 2) {
                        axios.post('{{ route('api.client.search') }}', {
                            terms: this.terms
                        }).then((response) => {
                            this.clients = response.data;
                        }).catch((e) => {
                            console.log(e);
                        })
                    }
                },
                selectClient(index) {
                    this.selectedClient = this.clients[index];
                    this.clients = [];
                    this.terms = '';
                },
                calculateEndDate() {
                    if (isNaN(+this.months)) {
                        this.months = 4;
                    }
                    let date = new Date();
                    date.setMonth(date.getMonth() + +this.months);
                    this.endDate = date.toISOString().substr(0, 10);
                },
                csrf: '{{ csrf_token() }}'
            }
        }
    </script>
@endsection
