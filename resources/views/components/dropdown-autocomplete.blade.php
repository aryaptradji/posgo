@props([
    'name' => '',
    'items' => [],
    'route' => '',
    'value' => '',
    'errorClass' => ''
])

<div>
    <label for="{{ $name }}"
        class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div x-data="{
        items: @js($items),
        selectedUserId: '{{ $value }}',
        search: '',
        get selectedName() {
            const item = this.items.find(i => i.id == this.selectedUserId);
            return item ? item.name : '';
        },
        creatingNew: false,
        open: false,
        get filteredItems() {
            return this.items.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        selectUser(user) {
            this.selectedUserId = user.id;
            this.search = user.name;
            this.creatingNew = false;
            this.open = false;
        },
        createNewUser() {
            fetch('{{ $route }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: this.search })
                })
                .then(response => response.json())
                .then(data => {
                    this.selectedUserId = data.id;
                    this.search = data.name;
                    this.creatingNew = false;
                    this.items.push({ id: data.id, name: data.name });
                    this.open = false;
                })
                .catch(error => {
                    console.error(error);
                    alert('Gagal menambahkan user baru');
                });
        }
    }" class="relative w-full">

        <!-- Input Search -->
        <input {{ $attributes }} type="text" x-model="search" :placeholder="selectedName || 'Cari / Tambah Nama Customer'"
            @focus="if (search === '') search = selectedName; open = true" @input="creatingNew = false; open = true"
            class="{{ $errorClass }} bg-tertiary h-14 shadow-outer focus:shadow-inner rounded-2xl text-black placeholder:text-tertiary-200 focus:border-[3.5px] focus:border-primary text-sm outline-none w-full text-left px-6">


        <!-- Hidden input untuk submit value -->
        <input type="hidden" name="{{ $name }}" x-bind:value="selectedUserId">

        <!-- Dropdown suggestion -->
        <div x-show="open && search.length > 0" x-cloak @click.away="open = false"
            class="absolute mt-1 w-full bg-white rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto border border-gray-200">

            <!-- Jika ketemu -->
            <template x-if="filteredItems.length > 0">
                <ul>
                    <template x-for="item in filteredItems" :key="item.id">
                        <li class="px-4 py-2 hover:bg-primary hover:text-white cursor-pointer"
                            @click="selectUser(item)">
                            <span x-text="item.name"></span>
                        </li>
                    </template>
                </ul>
            </template>

            <!-- Jika tidak ketemu -->
            <template x-if="filteredItems.length === 0">
                <div class="px-4 py-2 text-sm text-gray-700 cursor-pointer group hover:bg-primary hover:text-white flex gap-1 items-center"
                    @click="creatingNew = true; createNewUser()">
                    <x-icons.plus class="w-4 text-primary group-hover:text-white"/>
                    Tambah nama baru "<span x-text="search"></span>"
                </div>
            </template>
        </div>
    </div>
</div>
