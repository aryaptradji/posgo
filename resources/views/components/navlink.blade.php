@props([
    'active' => false,
    'focusNo' => null,
    'title' => null
])

<li>
    <a {{ $attributes }}
        x-bind:class="focus == {{ $focusNo }} || {{ $active }} ? 'text-white bg-primary shadow-outer-sidebar-primary' : 'text-black bg-none shadow-none transition-transform hover:scale-105'"
        class="flex items-center px-4 py-3 rounded-2xl group"
        {{ $active ? 'id="active-nav-link"' : '' }}>
        <span x-bind:class="focus == {{ $focusNo }} || {{ $active }} ? 'text-white' : 'text-black'">{{ $slot }}</span>
        <span class="ms-3 font-semibold">{{ $title }}</span>
    </a>
</li>
