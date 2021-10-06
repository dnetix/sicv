@extends('layouts.base')

@section('content')
    <form action="#" method="POST" x-data="{ amount: 0, displayAmount: '', percent: 10 }">
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <div class="shadow">
                    <div class="px-4 py-4 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Nuevo contrato</h3>
                        <p class="mt-1 text-sm text-gray-500">Generar un nuevo contrato a cliente</p>
                    </div>

                    <div class="bg-white py-6 px-4 space-y-6">

                        <div class="col-span-3">
                            <label for="last-name" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" name="last-name" id="last-name" placeholder="Buscar cliente" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="bg-white shadow overflow-hidden">
                            <ul role="list" class="divide-y divide-gray-200">
                                <li>
                                    <a href="#" class="block hover:bg-gray-50">
                                        <div class="flex items-center px-4 py-4">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                                </div>
                                                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-indigo-600 truncate">Ricardo
                                                            Cooper</p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                                            <i class="fa fa-envelope mr-1.5 text-gray-400"></i>
                                                            <span class="truncate">ricardo.cooper@example.com</span>
                                                        </p>
                                                    </div>
                                                    <div class="hidden md:block">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                Applied on
                                                                <time datetime="2020-01-07">January 7, 2020</time>
                                                            </p>
                                                            <p class="mt-2 flex items-center text-sm text-gray-500">
                                                                <!-- Heroicon name: solid/check-circle -->
                                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Completed phone screening
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fa fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </div>
                                    </a>
                                </li>
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
                                <label for="contract-start" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                                <input type="text" name="contract-start" id="contract-start" disabled value="{{ \App\Helpers\Dates\DateHelper::create()->toSQLDate() }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="col-span-6 lg:col-span-2">
                                <label for="contract-end" class="block text-sm font-medium text-gray-700">Fecha finalización</label>
                                <input type="text" name="contract-end" id="contract-end" disabled value="{{ \App\Helpers\Dates\DateHelper::create('+4 months')->toSQLDate() }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right">
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
                            <input type="text" name="state" id="state" value="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="">
                            <label for="percent" class="block text-sm font-medium text-gray-700">Porcentaje compra</label>
                            <input type="text" name="percent" id="percent" x-model="percent" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        function formatMoney(number) {
            return new Intl.NumberFormat('en-US').format(number);
        }
    </script>
@endsection
