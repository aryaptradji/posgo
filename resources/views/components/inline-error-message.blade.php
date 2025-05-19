<span {{ $attributes->merge(['class' => 'text-danger text-sm font-semibold ml-2 mb-2 -mt-2 block']) }} x-transition:enter="transition-all ease duration-700"
    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition-all ease duration-700" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2">
    {{ $slot }}
</span>
