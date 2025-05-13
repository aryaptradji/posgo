@props([
    'pageCount' => []
])

<div {{ $attributes->merge(['class' => 'relative']) }} x-ref="pagination"
    x-data="{
        open: false,
        perPage: 2,
        pageCount: @js($pageCount),
        currentPage: 1,
        setPagination(per, current) {
            this.perPage = per;
            this.currentPage = current;
        }
    }">
    <!-- Dropdown Per Page -->
    <button class="flex items-center px-3 bg-tertiary ring-1 ring-tertiary-300 rounded-lg text-sm" type="button"
        @click="open = !open">
        <span class="pe-3 py-2 border-r border-tertiary-300 text-tertiary-300">Per page</span>
        <span class="px-3 py-2" x-text="perPage"></span>
        <x-icons.arrow-down />
    </button>
    <div x-show="open" @click.away="open = false" class="absolute bottom-10 right-0 bg-white rounded-lg shadow-sm w-14">
        <ul class="py-2 text-sm text-gray-700">
            <template x-for="(count, index) in pageCount" :key="index">
                <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                    @click="perPage = count; open = false; currentPage = 1">
                    <span x-text="count"></span>
                </li>
            </template>
        </ul>
    </div>
</div>
