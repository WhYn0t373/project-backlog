@props(['type' => 'button', 'label' => null])

<button
    type="{{ $type }}"
    {{ $label ? 'aria-label="' . e($label) . '"' : '' }}
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']) }}
>
    {{ $slot }}
</button>