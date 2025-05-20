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
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }
}">
    <label for="{{ $id }}" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>
    <input inputmode="numeric" type="text" id="{{ $id }}"
    {{ $attributes->merge([
        'class' => 'bg-tertiary h-14 rounded-2xl shadow-outer text-black text-sm focus:shadow-inner outline-none placeholder-tertiary-200 w-full p-6'
    ]) }}
    @input="$nextTick(() => {
        raw = $el.value.replace(/[^\d]/g, '').replace(/^0+/, '');
        $el.value = formatRupiah(raw);
    })"
    x-bind:value="formatRupiah(raw)"
    {{ $required ? 'required' : null }}>
    <input type="hidden" name="{{ $name }}" :value="raw">
</div>
