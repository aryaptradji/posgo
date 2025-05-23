@props([
    'name' => null,
    'id' => null,
    'placeholder' => null,
    'classCont' => null,
    'required' => false,
])

<div class="{{ $classCont }}">
    <label for="{{ $id }}" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <div class="flex items-center relative" x-data="{ show: false }">
        <input
            {{ $attributes->merge([
                'class' => 'bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6'
            ]) }}
            :type="show ? 'text' : 'password'"
            name="{{ $name }}"
            id="{{ $id }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : null }}>
        <button type="button" class="absolute right-6 flex items-center" @click="show = !show">
            <x-icons.eye-open x-cloak x-show="!show"/>
            <x-icons.eye-close x-cloak x-show="show"/>
        </button>
    </div>
</div>
