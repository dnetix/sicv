@props(['label'])
<div>
    <span class="text-sm font-medium text-gray-500">{{ $label }}</span>
    <div class="mb-1 text-sm text-gray-900">{{ $slot }}</div>
</div>
