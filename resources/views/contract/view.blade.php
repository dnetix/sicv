@extends('layouts.base')

@section('content')
    <div x-data="app()">
        @csrf
        <div class="flex w-5/6 m-auto">
            <div class="w-3/4 p-2">

                <x-design.card title="{{ trans('contract.view_title', ['id' => $contract->id()]) }}" subtitle="{{ $contract->present()->state() }}" body-class="p-6">
                    <div class="mb-4">
                        <x-item label="{{ trans('client.client') }}">
                            <div>{{ $client->present()->name() }}</div>
                            <div>{{ $client->present()->document() }}</div>
                            <div>{{ $client->present()->mobile() }}</div>
                        </x-item>
                        <x-item label="{{ trans('contract.start_date') }}">{{ $contract->present()->date() }}</x-item>
                        <x-item label="{{ trans('contract.end_date') }}">{{ $contract->present()->endDate() }}</x-item>
                        <x-item label="{{ trans('contract.total') }}">{{ $contract->present()->amount() }}</x-item>
                        <x-item label="{{ trans('contract.extension') }}">{{ $contract->present()->extension() }}</x-item>
                        <x-item label="{{ trans('contract.contract_months') }}">{{ $contract->present()->contractMonths() }}</x-item>
                        <x-item label="{{ trans('contract.articles') }}">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach($contract->articles as $article)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center rows">
                                            <div>
                                                <span class="ml-2">{{ $article->present()->description() }}</span>
                                                <span class="ml-2 text-sm text-gray-300">{{ $article->present()->articleType() }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <span class="font-medium text-indigo-600 hover:text-indigo-500">{{ $article->present()->articleAmount() }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </x-item>
                    </div>
                </x-design.card>

            </div>

            <div class="w-1/4 p-2">

                <x-design.card title="{{ trans('contract.extension') }}">
                    @foreach($extensions as $extension)
                        <div class="flex items-center space-x-4">
                            <div class="text-lg">{{ $extension->present()->amount() }}</div>
                            <div class="text-sm text-gray-600">{{ $extension->present()->createdAt() }}</div>
                        </div>
                    @endforeach
                    @if($contract->isActive())
                        <form action="{{ route('contract.extension', ['contract' => $contract->id()]) }}" method="POST">
                            @csrf
                            <x-input label="{{ trans('contract.extension_amount') }}" name="amount" id="amount" type="number" required></x-input>
                            <x-button type="submit">{{ trans('contract.extension_store') }}</x-button>
                        </form>
                    @endif
                </x-design.card>

                <x-design.card title="{{ trans('contract.note_title') }}">
                    @foreach($notes as $note)
                        <div class="{{ $note->present()->class() }} p-4 rounded mb-2">
                            <div class="mb-2">{{ $note->note() }}</div>
                            <div class="text-sm font-bold">{{ $note->present()->createdAt() }}</div>
                            <div class="text-right text-sm">{{ $note->present()->user() }}</div>
                        </div>
                    @endforeach

                    <form action="{{ route('contract.note', ['contract' => $contract->id()]) }}" method="POST">
                        <div class="">
                            @csrf
                            <x-textarea label="{{ trans('client.note') }}" id="note" name="note" required></x-textarea>
                            <x-select label="{{ trans('client.note_importance') }}" id="importance" name="importance" required>
                                @foreach(\App\Models\Clients\ClientNote::$LEVELS as $value)
                                    <option value="{{ $value }}">{{ trans('client.note_' . $value) }}</option>
                                @endforeach
                            </x-select>
                            <x-button type="submit">{{ trans('contract.note_submit') }}</x-button>
                        </div>
                    </form>
                </x-design.card>

            </div>
        </div>
    </div>

    <script>
        function app() {
            return {
                validate() {
                    return true;
                }
            }
        }
    </script>
@endsection
