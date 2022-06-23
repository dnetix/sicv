@extends('layouts.base')

@section('content')
    <form action="{{ route('client.store') }}" method="POST" x-data="app()" @submit="validate">
        @csrf
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <x-design.card title="{{ trans('client.create_title') }}">
                    <form action="{{ route('client.store') }}">
                        <div class="space-y-4">
                            <div class="w-5/6">
                                <x-input label="{{ trans('client.name') }}" id="name" name="name" required></x-input>
                            </div>
                            <div class="flex space-x-4">
                                <div class="flex-auto w-1/6">
                                    <x-select label="{{ trans('client.document_type') }}" id="document_type" name="document_type">
                                        @foreach($documentTypes as $type => $name)
                                            <option value="{{ $type }}">{{ $name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="flex-auto w-1/2">
                                    <x-input label="{{ trans('client.document') }}" id="document" name="document" required></x-input>
                                </div>
                                <div class="flex-auto w-1/3">
                                    <x-input label="{{ trans('client.expedition_city') }}" id="expedition_city" name="expedition_city" required></x-input>
                                </div>
                            </div>
                            <div class="w-2/3">
                                <x-input label="{{ trans('client.mobile') }}" id="mobile" name="mobile" required></x-input>
                            </div>
                            <div class="flex space-x-4">
                                <div class="flex-auto w-1/3">
                                    <x-input label="{{ trans('client.email') }}" id="email" name="email"></x-input>
                                </div>
                                <div class="flex-auto w-1/3">
                                    <x-input label="{{ trans('client.phone_number') }}" id="phone_number" name="phone_number"></x-input>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <x-button type="submit">{{ trans('client.submit') }}</x-button>
                            </div>
                        </div>
                    </form>
                </x-design.card>

            </div>

            <div class="w-1/4 p-2">

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
