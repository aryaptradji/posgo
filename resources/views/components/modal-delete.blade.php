@props([
    'route' => '', // base route, misal: 'product'
    'title' => 'Hapus Data',
    'label' => 'data ini',
])

<div x-data="{
    show: false,
    id: null,
    name: '',
    open(id, name) {
        this.id = id;
        this.name = name;
        this.show = true;
    },
    get deleteUrl() {
        return `/admin/{{ $route }}/${this.id}`;
    }
}" x-show="show" x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div class="bg-white rounded-xl p-6 w-[90%] max-w-sm shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <x-icons.delete-icon class="text-danger" />
            <h3 class="text-lg font-bold">{{ $title }}</h3>
        </div>

        <p class="text-sm mb-6">
            Kamu ingin menghapus {{ $label }} <strong x-text="name"></strong>?
        </p>

        <form :action="deleteUrl" method="POST" class="flex justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button" @click="show = false"
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-danger text-white font-semibold hover:bg-red-600">
                Hapus
            </button>
        </form>
    </div>
</div>
