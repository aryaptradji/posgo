@props([
    'show' => false,
    'iconTitle' => null,
    'title' => null,
    'action' => null
])

<div class="fixed flex justify-center inset-0 z-50 pt-16 bg-black/20 backdrop-blur-sm" x-cloak x-show="{{ $show }}"
    x-transition:enter="transition-all ease duration-700" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-all ease duration-700"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @click.self="{{ $show }} = false">
    <div x-cloak x-show="{{ $show }}" x-transition:enter="transition-all ease duration-700"
        x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition-all ease duration-700" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full" class="bg-tertiary py-4 h-fit rounded-xl max-w-sm w-full">
        <div class="flex justify-start border-b-2 ps-4 pb-4 border-b-tertiary-title-line">
            {{ $iconTitle }}
            <h2 class="text-lg font-bold">{{ $title }}</h2>
        </div>
        <p class="mb-6 ml-6 mt-4 text-start">
            {{ $slot }}
        </p>
        <div class="flex justify-end pe-4 gap-3">
            {{ $action }}
        </div>
    </div>
</div>
