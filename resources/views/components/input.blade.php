@props(['label'])

<div>
    <label for="{{ $attributes->get('id') }}" class="block text-sm font-medium text-gray-700">{{ $label }}
        <input
            {{ $attributes->merge([
                'type' => 'text',
                'class' => 'mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500'
            ]) }}
            />
    </label>
</div>
