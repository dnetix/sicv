@extends('layouts.base')

@section('content')
    <form action="{{ route('contract.store') }}" method="POST" x-data="app()" @submit="validate">
        @csrf
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <x-design.card title="{{ trans('contract.new_contract_card_title') }}" subtitle="{{ trans('contract.new_contract_card_subtitle') }}" body-class="p-6">
                    <div class="mb-4">
                        <x-input label="{{ trans('client.search_label') }}" name="terms" id="terms" x-on:keyup.debounce="searchClient" x-model="terms" placeholder="{{ trans('client.search_placeholder') }}"></x-input>

                        <template x-if="selectedClient ? true : false">
                            <div class="bg-gray-100 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-auto w-1/3">
                                        <div class="text-lg font-bold text-indigo-600">
                                            <span x-text="selectedClient.name">Diego Arturo Calle Mora</span>
                                        </div>
                                        <div class="text-sm text-gray-800 font-bold">
                                            <span x-text="`${selectedClient.document_type} ${selectedClient.document}`">CC 1040025062</span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            <span x-text="selectedClient.expedition_city">La Ceja Antioquia</span>
                                        </div>
                                    </div>
                                    <div class="flex-auto w-1/3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            @svg('heroicon-o-device-mobile', 'flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400')
                                            <span x-text="`${selectedClient.mobile ?? ''} ${selectedClient.phone_number ?? ''}`">3006108300</span>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            @svg('heroicon-o-location-marker', 'flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400')
                                            <span x-text="selectedClient.city ?? 'No tiene'">Carrera 74 # 53-162</span>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600">
                                            @svg('heroicon-o-mail', 'flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400')
                                            <span x-text="selectedClient.email ?? 'No tiene'">dnetix@gmail.com</span>
                                        </div>
                                    </div>
                                    <div class="flex-auto w-1/3">Badges</div>
                                </div>
                            </div>
                        </template>

                        <template x-if="clients.length > 0">
                            <ul role="list" class="divide-y divide-gray-200">
                                <template x-for="(client, index) in clients">
                                    <li>
                                        <a href="javascript:;" x-on:click="selectClient(index)"
                                           class="block hover:bg-gray-100">
                                            <div class="flex py-2 px-4 gap-4 items-center">
                                                <div class="flex items-center gap-4 flex-auto">
                                                    <div class="text-indigo-500 px-3">
                                                        <i class="fa fa-user-alt"></i>
                                                    </div>
                                                    <span class="w-3/6 text-gray-800" x-text="client.name">Diego Arturo Calle</span>
                                                    <span class="w-1/6 text-sm text-gray-700" x-text="`${client.document_type} ${client.document}`">CC 1040035072</span>
                                                    <span class="w-1/6 text-sm text-gray-700" x-text="`${client.mobile ?? ''} ${client.phone_number ?? ''}`">3006108300</span>
                                                </div>
                                                <div class="text-gray-400">
                                                    <i class="fa fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </template>

                        <template x-if="!selectedClient && emptyResponse">
                            <div class="p-4 text-center bg-gray-100 p-4 mb-4">
                                <h3 class="text-sm font-medium text-gray-900">{{ trans('client.empty_search') }}</h3>
                                <div class="mt-3">
                                    <a href="{{ route('client.new') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        @svg('heroicon-o-plus', '-ml-1 mr-2 h-5 w-5') {{ trans('client.new_label') }}
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>

                    <template x-for="(article, index) in articles">
                        <div class="bg-gray-50 grid grid-cols-12 gap-3 mb-4 p-4">
                            <div class="col-span-11">
                                <x-textarea label="{{ trans('contract.article_label') }}" id="description" name="description[]" placeholder="{{ trans('contract.article_description') }}" x-model="article.description"></x-textarea>
                            </div>

                            <div x-show="index != 0" class="grid-cols-1 text-center flex justify-center items-center">
                                <x-button color="red" x-on:click="removeArticle(index)"><i class="fa fa-times"></i></x-button>
                            </div>

                            <div class="col-span-6">
                                <x-select label="{{ trans('contract.article_type') }}" id="article_type" name="article_type_id[]" x-model="article.article_type_id" required>
                                    <option value="">{{ trans('app.select') }}</option>
                                    <template x-for="articleType in articleTypes">
                                        <option :value="articleType.id" x-text="articleType.article_type"></option>
                                    </template>
                                </x-select>
                            </div>

                            <div class="col-span-3">
                                <x-input label="Peso" id="weight" name="weight[]" x-model="article.weight" placeholder="Gramos" step="0.01" autocomplete="off"></x-input>
                            </div>

                            <div class="col-span-3">
                                <x-input label="Valor" id="amount" name="amount[]" x-model="article.amount" x-on:change="updateAmount(index)" autocomplete="off"></x-input>
                            </div>
                        </div>
                    </template>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-3">
                            <x-input label="{{ trans('contract.start_date') }}" id="contract-start" name="contract-start" value="{{ \App\Helpers\Dates\DateHelper::create()->toSQLDate() }}" disabled></x-input>
                        </div>

                        <div class="col-span-3">
                            <x-input label="{{ trans('contract.end_date') }}" id="contract-end" name="contract-end" x-model="endDate" disabled></x-input>
                        </div>

                        <div class="col-span-3">
                            <x-input label="{{ trans('contract.total') }}" id="total" name="total" label="Total" x-bind:value="formatMoney(amount)" disabled></x-input>
                        </div>

                        <div class="col-span-3">
                            <x-input label="{{ trans('contract.extension') }}" id="extension" name="extension" x-bind:value="formatMoney(amount * (percentage / 100))" disabled></x-input>
                        </div>
                    </div>

                    <input type="hidden" x-model="selectedClient?.id" name="client_id" required>

                    <div class="flex justify-end mt-4">
                        <x-button color="gray" class="mx-2" x-on:click="addArticle">@svg('heroicon-o-plus', 'w-5 mr-1 -ml-1') {{ trans('contract.add_article') }}</x-button>
                        <x-button class="mx-2" type="submit">@svg('heroicon-o-document-add', 'w-5 mr-1 -ml-1') {{ trans('contract.save') }}</x-button>
                    </div>
                </x-design.card>

            </div>

            <div class="w-1/4 p-2">

                <x-design.card title="{{ trans('contract.type') }}">
                    <div class="">
                        <x-input label="{{ trans('contract.months') }}" id="months" name="months" x-model="months" x-on:change="calculateEndDate"></x-input>
                    </div>

                    <div class="">
                        <x-input label="{{ trans('contract.percentage') }}" id="percentage" name="percentage" x-model="percentage"></x-input>
                    </div>
                </x-design.card>

                <x-design.card title="{{ trans('contract.note_title') }}">
                    <div class="">
                        <x-textarea label="{{ trans('client.note') }}" id="note" name="note"></x-textarea>
                    </div>

                    <div class="">
                        <x-select label="{{ trans('client.note_importance') }}" id="importance" name="importance">
                            @foreach(\App\Models\Clients\ClientNote::$LEVELS as $value)
                                <option value="{{ $value }}">{{ trans('client.note_' . $value) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </x-design.card>

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
                        this.selectedClient = null;

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
                        let value = +item.amount?.replace(',', '');
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
