@props([
    'type' => null,
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'classCont' => null,
    'required' => false,
    'value' => null,
])

<div class="{{ $classCont }}">
    <label for="{{ $id }}" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6'
        ]) }}
    >
</div>
