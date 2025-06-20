@props([
    'name' => '',
    'items' => [],
    'route' => '',
    'value' => '',
    'errorClass' => ''
])

<div>
    <label for="{{ $name }}" class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div
        x-data="autocompletePro({
            items: @js($items),
            initialValue: '{{ $value }}',
            route: '{{ $route }}',
            csrfToken: '{{ csrf_token() }}'
        })"
        class="relative w-full"
    >
        <!-- Input Search -->
        <input
            {{ $attributes }}
            type="text"
            x-model="search"
            :placeholder="selectedName || 'Cari / Tambah Nama Customer'"
            @focus="handleFocus()"
            @input="handleInput()"
            @keydown.arrow-down.prevent="moveDown()"
            @keydown.arrow-up.prevent="moveUp()"
            @keydown.enter.prevent="handleEnter()"
            :class="errorClass"
            class="bg-tertiary h-14 shadow-outer focus:shadow-inner rounded-2xl text-black placeholder:text-tertiary-200 focus:border-[3.5px] focus:border-primary text-sm outline-none w-full text-left px-6"
        >

        <!-- Hidden input untuk submit id -->
        <input type="hidden" name="{{ $name }}" :value="selectedUserId">

        <!-- Dropdown -->
        <div
            x-show="open"
            x-transition
            @click.away="open = false"
            class="absolute mt-1 w-full bg-white rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto border border-gray-200"
        >
            <template x-if="isLoading">
                <div class="flex justify-center items-center p-4">
                    <svg class="animate-spin h-5 w-5 text-primary" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                </div>
            </template>

            <template x-if="!isLoading && filteredItems.length > 0">
                <ul>
                    <template x-for="(item, index) in filteredItems" :key="item.id">
                        <li
                            :class="activeIndex === index ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white'"
                            class="px-4 py-2 cursor-pointer"
                            @mouseenter="activeIndex = index"
                            @mouseleave="activeIndex = -1"
                            @click="selectUser(item)"
                        >
                            <span x-text="item.name"></span>
                        </li>
                    </template>
                </ul>
            </template>

            <template x-if="!isLoading && filteredItems.length === 0">
                <div
                    class="px-4 py-2 text-sm text-gray-700 cursor-pointer group hover:bg-primary hover:text-white flex gap-1 items-center"
                    @click="createNewUser()"
                >
                    <x-icons.plus class="w-4 text-primary group-hover:text-white"/>
                    Tambah nama baru "<span x-text="search"></span>"
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Inline Alpine Component -->
<script>
    function autocompletePro({ items, initialValue, route, csrfToken }) {
        return {
            items,
            route,
            csrfToken,

            search: '',
            selectedUserId: initialValue,
            open: false,
            isLoading: false,
            activeIndex: -1,

            get selectedName() {
                const item = this.items.find(i => i.id == this.selectedUserId);
                return item ? item.name : '';
            },

            get filteredItems() {
                if (this.search === '') return this.items;
                return this.items.filter(i =>
                    i.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            handleFocus() {
                this.search = this.selectedName || '';
                this.open = true;
            },

            handleInput() {
                this.open = true;
                this.activeIndex = -1;
                this.debounceFetch();
            },

            handleEnter() {
                if (this.activeIndex >= 0 && this.filteredItems[this.activeIndex]) {
                    this.selectUser(this.filteredItems[this.activeIndex]);
                } else if (this.filteredItems.length === 1) {
                    this.selectUser(this.filteredItems[0]);
                }
            },

            moveDown() {
                if (this.activeIndex < this.filteredItems.length - 1) {
                    this.activeIndex++;
                }
            },

            moveUp() {
                if (this.activeIndex > 0) {
                    this.activeIndex--;
                }
            },

            selectUser(user) {
                this.selectedUserId = user.id;
                this.search = user.name;
                this.open = false;
            },

            createNewUser() {
                if (this.search.trim() === '') return;
                this.isLoading = true;

                fetch(this.route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ name: this.search })
                })
                .then(response => response.json())
                .then(data => {
                    this.items.push({ id: data.id, name: data.name });
                    this.selectedUserId = data.id;
                    this.search = data.name;
                    this.open = false;
                })
                .catch(() => alert('Gagal menambahkan user baru'))
                .finally(() => this.isLoading = false);
            },

            debounceFetch: debounce(function() {
                this.isLoading = true;
                setTimeout(() => { this.isLoading = false; }, 300);
            }, 300)
        }
    }

    function debounce(func, delay) {
        let timeout;
        return function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, arguments), delay);
        }
    }
</script>
