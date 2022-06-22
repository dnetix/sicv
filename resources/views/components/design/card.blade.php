@props(['title' => '', 'subtitle' => ''])

<div>
    <div class="px-4 py-4 bg-gray-50">
        <h1 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h1>
        @if($subtitle)
            <h2 class="mt-1 text-sm text-gray-500">{{ $subtitle }}</h2>
        @endif
    </div>
    <div class="p-4 {{ $attributes->get('body-class') }}">
        {{ $slot }}
    </div>
</div>
