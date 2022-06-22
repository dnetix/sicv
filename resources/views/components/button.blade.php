@props(['color' => 'indigo'])

<div>
    <button
        {{ $attributes->merge([
            'class' => "bg-{$color}-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-{$color}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{$color}-500",
            'type' => 'button',
        ]) }}>{{ $slot }}</button>
</div>
