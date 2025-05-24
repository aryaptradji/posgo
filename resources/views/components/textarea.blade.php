@props([
    'name' => null,
    'placeholder' => null,
    'classCont' => null,
    'value' => null
])

<div class="{{ $classCont }}">
    <label for="{{ $name }}" class="block mb-4 text-base font-bold text-black">{{ $slot }}</label>
    <textarea
        {{ $attributes->merge(['class' => 'resize-none bg-tertiary h-64 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6']) }}
        name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}">{{ $value }}</textarea>
</div>
