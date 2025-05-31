@props([
    'contClass' => null,
    'type' => null
])

<div class="{{ $contClass }}">
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'w-full py-3 rounded-full text-white tracking-widest font-bold uppercase transition-transform active:scale-90 hover:scale-105 duration-300']) }}>{{ $slot }}</button>
</div>
