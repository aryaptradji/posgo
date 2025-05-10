<button {{ $attributes }} type="{{ $type }}"
    class="w-1/4 py-3 rounded-full {{ $color }} tracking-widest font-bold uppercase {{ $background }} transition-transform active:scale-90 hover:scale-105 duration-300">
    {{ $slot }}
</button>
