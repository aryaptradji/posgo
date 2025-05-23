<x-layout class="mb-0">
    <x-slot:title>Buat Pengeluaran</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('expense.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Pengeluaran</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Ubah</span>
        </div>
        <div>
            Ubah Pengeluaran
        </div>
    </x-slot:header>

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col items-end gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25"
                    slotClass="text-danger" :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed />
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif

    <form action="{{ route('expense.update', $expense) }}" method="POST" enctype="multipart/form-data" class="mt-10 rounded-xl grid grid-cols-2 gap-8"
        x-data="{
            sourceServerError: {{ $errors->has('source') ? true : false }},
            categoryServerError: {{ $errors->has('category') ? true : false }},
        }"
        >
        @csrf
        @method('PUT')
        <div class="col-span-1 flex flex-col gap-4">
            {{-- Waktu --}}
            <x-textfield type="datetime-local" name="date" :value="old('date', $expense->date)" class="focus-within:ring focus-within:ring-primary" classCont="mb-2">
                Waktu
            </x-textfield>

            {{-- Sumber --}}
            <x-dropdown-toggle class="mb-2" name="source" :items="array_merge($products, ['Gaji Karyawan', 'Bayar Listrik', 'Bayar Air', 'Biaya Kebershihan', 'Biaya Transportasi'])" :value="old('source', $expense->source)">Sumber</x-dropdown-toggle>
            @error('source')
                <x-inline-error-message class="mb-2 -mt-2" x-show="sourceServerError">{{ $message }}</x-inline-error-message>
            @enderror
        </div>
        <div class="col-span-1 flex flex-col gap-4">
            {{-- Kategori --}}
            <x-dropdown class="mb-2" name="category" :items="['operasional', 'luar operasional']" :value="old('category', $expense->category)">Kategori</x-dropdown>
            @error('category')
                <x-inline-error-message class="mb-2 -mt-2" x-show="categoryServerError">{{ $message }}</x-inline-error-message>
            @enderror

            {{-- Total --}}
            <x-textfield-price class="focus:ring focus:ring-primary" name="total" :value="old('total', $expense->total)">Total</x-textfield-price>
        </div>
        <div class="col-span-2 flex justify-center gap-6 mt-3">
            <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                <a href="{{ route('expense.index') }}">Batal</a>
            </x-button-sm>
            <x-button-sm type="submit" class="w-fit px-7 text-primary bg-primary/20">Simpan</x-button-sm>
        </div>
    </form>
</x-layout>
