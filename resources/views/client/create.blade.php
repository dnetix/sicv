@extends('layouts.base')

@section('content')
    <form x-data="app()" @submit.prevent="submitForm">
        @csrf
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <x-design.card title="{{ trans('client.create_title') }}">
                    <div class="space-y-4">
                        <div class="w-5/6">
                            <x-input label="{{ trans('client.name') }}" id="name" name="name" x-model="client.name" required></x-input>
                        </div>
                        <div class="flex space-x-4">
                            <div class="flex-auto w-1/6">
                                <x-select label="{{ trans('client.document_type') }}" id="document_type" name="document_type" x-model="client.document_type">
                                    @foreach($documentTypes as $type => $name)
                                        <option value="{{ $type }}">{{ $name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="flex-auto w-1/2">
                                <x-input label="{{ trans('client.document') }}" id="document" name="document" x-model="client.document" required></x-input>
                            </div>
                            <div class="flex-auto w-1/3">
                                <x-input label="{{ trans('client.expedition_city') }}" id="expedition_city" name="expedition_city" x-model="client.expedition_city" required></x-input>
                            </div>
                        </div>
                        <div class="w-2/3">
                            <x-input label="{{ trans('client.mobile') }}" id="mobile" name="mobile" x-model="client.mobile" required></x-input>
                        </div>
                        <div class="flex space-x-4">
                            <div class="flex-auto w-1/3">
                                <x-input label="{{ trans('client.email') }}" id="email" name="email" x-model="client.email"></x-input>
                            </div>
                            <div class="flex-auto w-1/3">
                                <x-input label="{{ trans('client.phone_number') }}" id="phone_number" name="phone_number" x-model="client.phone_number"></x-input>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <x-button type="submit">{{ trans('client.submit') }}</x-button>
                        </div>
                    </div>
                </x-design.card>

            </div>

            <div class="w-1/4 p-2">
                <x-validation-errors-ajax></x-validation-errors-ajax>
            </div>
        </div>
    </form>

    <script>
        function app() {
            return {
                errors: [],
                client: {
                    name: null,
                    document_type: 'CC',
                    document: null,
                    expedition_city: null,
                    email: null,
                    mobile: null,
                    phone_number: null
                },
                submitForm() {
                    this.errors = [];
                    axios.post('{{ route('api.client.store') }}', {
                        ...this.client,
                        _token: "{{ csrf_token() }}"
                    }).then(response => {
                        location.href = '{{ route('contract.new') }}/' + response.data.id;
                    }).catch(e => {
                        bag = e.response.data.errors;
                        for(key in bag) {
                            this.errors = [...this.errors, ...bag[key]];
                        }
                    });
                }
            }
        }
    </script>
@endsection
