@extends('layouts.base')

@section('content')
    <div class="flex w-5/6 m-auto">
        <div class="w-3/4 p-2">

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contrato
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monto
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @for($i=0; $i<15; $i++)
                                    <tr>
                                        <td class="px-6 py-4 text-left">
                                            <div class="text-gray-900">33122</div>
                                            <div class="text-sm text-gray-500">2021-09-12 08:00:00</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Pulidora Black Decker   G720 B3   ( sin disco )</div>
                                            <div class="text-sm text-gray-500">Yuber Alberto Osorio Macias</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            $ 50.000
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-1/4 p-2">

            <div class="w-full inline-block align-bottom bg-gray-100 rounded-lg text-left overflow-hidden">
                <div class="px-4 pt-5">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-400">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="text-left pl-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Buscar clientes
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Busqueda por nombre o cedula
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 pt-2 pb-5">
                    <input type="text" name="price" id="price" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full border-gray-300 rounded-md" placeholder="">
                </div>
            </div>

        </div>
    </div>
@endsection
