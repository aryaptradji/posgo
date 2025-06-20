@props([
    'name' => null,
    'id' => null,
    'classCont' => null,
    'required' => false,
    'value' => 0
])

<div class="{{ $classCont }}" x-data="{
    raw: '{{ $value }}',
    formatRupiah(val) {
        val = val.replace(/^0+/, '').replace(/[^\d]/g, '');
        if (!val) return 'Rp 0';
        let number_string = val.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }
}">
    <label for="{{ $id }}" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <input
        inputmode="numeric"
        type="text"
        id="{{ $id }}"
        {{ $attributes->merge([
            'class' => 'bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6'
        ]) }}
        @input="
            raw = $el.value.replace(/[^\d]/g, '');
            $el.value = formatRupiah(raw);
            $dispatch('cashinput', { amount: parseInt(raw || 0) });
        "
        x-bind:value="formatRupiah(raw)"
        {{ $required ? 'required' : null }}>
    <input type="hidden" name="{{ $name }}" :value="raw">
</div>
