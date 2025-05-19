@props([
    'id' => 'toast',
    'icon' => null,
    'iconClass' => null,
    'slotClass' => null,
    'duration' => 3000,
    'delay' => 0
])

<div
    id="{{ $id }}"
    x-data="{ show: false }"
    x-init="setTimeout(() => {
         show = true;

         setTimeout(() => show = false, {{ $duration }})
    }, {{ $delay }})"
    x-show="show"
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white/20 backdrop-blur-sm rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
    role="alert"
>
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 {{ $iconClass }} rounded-lg">
        {{ $icon }}
        <span class="sr-only">Icon</span>
    </div>
    <div class="ms-3 pe-6 text-sm font-semibold {{ $slotClass }}">{{ $slot }}</div>
    <button type="button"
        @click="show = false"
        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
        aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
    </button>
</div>
