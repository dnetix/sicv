@extends('layouts.base')

@section('content')
    <form action="{{ route('contract.store') }}" method="POST" x-data="app()" @submit="validate">
        @csrf
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <div class="shadow">
                    <div class="px-4 py-4 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Nuevo contrato</h3>
                        <p class="mt-1 text-sm text-gray-500">Generar un nuevo contrato a cliente</p>
                    </div>

                    <div class="bg-white py-6 px-4 space-y-4">

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
                                            <p class="text-sm text-gray-600" x-text="`${selectedClient.id_type} ${selectedClient.id_number}`">
                                                CC 1040035000</p>
                                            <p class="text-sm text-gray-600" x-text="selectedClient.id_expedition">
                                                Abejorral</p>
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

                            <template x-if="!selectedClient && emptyResponse">
                                <div class="p-4 text-center">
                                    <h3 class="text-sm font-medium text-gray-900">No hay clientes que coincidan con la búsqueda</h3>
                                    <div class="mt-3">
                                        <a href="{{ route('client.new') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Nuevo Cliente
                                        </a>
                                    </div>
                                </div>
                            </template>

                        </div>

                        <template x-for="(article, index) in articles">
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-11">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Artículo</label>
                                    <textarea x-model="article.description" name="description[]" id="description" placeholder="Descripción del artículo" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>

                                <div x-show="index != 0" class="grid-cols-1 text-center">
                                    <button @click="removeArticle(index)" type="button" class="mt-9 bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>

                                <div class="col-span-6 pb-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tipo Artículo
                                    </label>
                                    <select x-model="article.article_type_id" name="article_type_id[]" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona</option>
                                        <template x-for="articleType in articleTypes">
                                            <option :value="articleType.id" x-text="articleType.article_type"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="col-span-3">
                                    <label for="weight" class="block text-sm font-medium text-gray-700">Peso</label>
                                    <input type="number" step="0.01" name="weight[]" x-model="article.weight" id="weight" placeholder="Gramos" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="col-span-3">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <input type="text" name="amount[]" x-model="article.amount" id="amount" @change="updateAmount(index)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </template>

                        <div class="text-right">
                            <button @click="addArticle" type="button" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-3">
                                <label for="contract-start" class="block text-sm font-medium text-gray-700">Fecha
                                    Inicio</label>
                                <input type="text" name="contract-start" id="contract-start" disabled value="{{ \App\Helpers\Dates\DateHelper::create()->toSQLDate() }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-3">
                                <label for="contract-end" class="block text-sm font-medium text-gray-700">Fecha
                                    finalización</label>
                                <input type="text" name="contract-end" id="contract-end" disabled x-model="endDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-3">
                                <label for="total" class="block text-sm font-medium text-gray-700">Total</label>
                                <input type="text" name="total" disabled id="total" :value="formatMoney(amount)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-3">
                                <label for="extension" class="block text-sm font-medium text-gray-700">Prorroga</label>
                                <input type="text" name="extension" disabled id="extension" :value="formatMoney(amount * (percentage / 100))" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                    <div class="px-4 pt-2 pb-5">
                        <div class="">
                            <label for="months" class="block text-sm font-medium text-gray-700">Meses</label>
                            <input type="text" name="months" id="months" x-model="months" x-on:change="calculateEndDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="">
                            <label for="percentage" class="block text-sm font-medium text-gray-700">Porcentaje compra</label>
                            <input type="text" name="percentage" id="percentage" x-model="percentage" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="w-full inline-block align-bottom rounded-lg text-left overflow-hidden bg-gray-50">
                    <div class="px-4 py-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Nota contrato</h3>
                    </div>
                    <div class="px-4 pt-2 pb-5">
                        <div class="">
                            <label for="note" class="block text-sm font-medium text-gray-700">Nota</label>
                            <textarea name="note" id="note" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>

                        <div class="">
                            <label for="importance" class="block text-sm font-medium text-gray-700">Tipo Nota</label>
                            <select name="importance" id="importance" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="info">Información</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function app() {
            return {
                articles: [{}],
                amount: 0,
                months: '4',
                displayAmount: '',
                emptyResponse: false,
                percentage: 10,
                articleTypes: {!! json_encode($articleTypes) !!},
                formatMoney(number) {
                    return new Intl.NumberFormat('en-US').format(number);
                },
                terms: '',
                selectedClient: {!! $client ? json_encode($client) : 'null' !!},
                clients: [],
                endDate: '{{ \App\Helpers\Dates\DateHelper::create('+4 months')->toSQLDate() }}',
                searchClient() {
                    if (this.terms.length > 2) {
                        this.emptyResponse = false;

                        axios.post('{{ route('api.client.search') }}', {
                            terms: this.terms
                        }).then((response) => {
                            if (response.data.length > 0) {
                                this.clients = response.data;
                            } else {
                                this.emptyResponse = true;
                            }
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
                addArticle() {
                    this.articles.push({});
                    this.updateAmount(0);
                },
                removeArticle(index) {
                    this.articles.splice(index, 1);
                    this.updateAmount(0);
                },
                updateAmount(index) {
                    this.amount = 0;
                    this.articles.map((item, index) => {
                        let value = +item.amount?.replace(',','');
                        if (value) {
                            this.amount += value;
                            item.amount = this.formatMoney(value);
                        }
                    });
                },
                validate() {
                    if (!this.selectedClient?.id) {
                        alert("Por favor selecciona un cliente");
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
@endsection
