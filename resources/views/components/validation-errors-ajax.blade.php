<template x-if="errors.length > 0">
    <div class="font-medium text-red-600">
        {{ trans('app.validation_errors_title') }}
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <template x-for="error in errors">
                <li x-text="error">Nothing here</li>
            </template>
        </ul>
    </div>
</template>
