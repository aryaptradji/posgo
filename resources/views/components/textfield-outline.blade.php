@props([
    'type' => null,
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'required' => false,
    'contClass' => null
])

<div {{ $contClass }}>
    <label for="{{ $id }}" class="block mb-4 text-base font-semibold text-black dark:text-white">{{ $slot }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : null }}
        {{ $attributes->merge(['class' => 'bg-tertiary h-14 rounded-xl text-black text-sm ring-1 ring-tertiary-300 outline-none placeholder-tertiary-200 w-full p-6']) }}>
</div>
