@props([
    'type' => null,
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'class' => null,
    'required' => false,
])

<div class="{{ $class }}">
    <label for="email" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <input
        {{ $attributes->merge([
            'class' => 'bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6'
        ]) }}
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : null }}
    >
</div>
