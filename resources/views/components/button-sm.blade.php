@props([
    'type' => 'button'
])

<button {{ $attributes->merge(['class' => 'w-1/4 py-3 rounded-full tracking-widest font-bold uppercase transition-transform active:scale-90 hover:scale-105 duration-300']) }} type="{{ $type }}">
    {{ $slot }}
</button>
