{{-- resources/views/components/textfield-price-outline.blade.php --}}
@props([
    'name' => null,
    'id' => null,
    'classCont' => null,
    'required' => false,
    'value' => 0, // Ini prop PHP
    'labelClass' => null,
    'readonly' => false,
])

<div class="{{ $classCont }}" x-data="{
    currentValue: Number({{ $value }}), // Konversi nilai prop menjadi angka
}">
    <label for="{{ $id }}" class="block text-base font-bold text-black {{ $labelClass }}">{{ $slot }}</label>
    <input
        inputmode="numeric"
        type="number"
        id="{{ $id }}"
        {{ $attributes->merge([
            'class' => 'bg-tertiary h-14 rounded-xl text-black text-sm ring-1 ring-tertiary-300 outline-none placeholder-tertiary-200 w-full p-6'
        ]) }}
        x-model.number="currentValue"
        x-bind:readonly="{{ $readonly ? 'true' : 'false' }}"
        {{ $required ? 'required' : null }}
        min="0"
        step="1"
    >
    <input type="hidden" name="{{ $name }}" x-model="currentValue">
</div>
