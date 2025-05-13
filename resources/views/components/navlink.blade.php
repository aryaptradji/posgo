@props([
    'focusNo' => null
])

<li>
    <a {{ $attributes }}
    x-bind:class="focus == {{ $focusNo }} ? 'text-white bg-primary shadow-outer-sidebar' : 'text-black bg-none shadow-none transition-transform hover:scale-105'"
    class="flex items-center px-4 py-3 rounded-2xl group">
        {{ $slot }}
    </a>
</li>
