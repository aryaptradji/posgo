<div {{ $attributes }}>
    <label for="email" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
        class="bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner focus:ring focus:ring-primary outline-none placeholder-tertiary-200 w-full p-6"
        placeholder="{{ $placeholder }}" required="{{ $required }}">
</div>
