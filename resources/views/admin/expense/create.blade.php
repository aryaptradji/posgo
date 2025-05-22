<x-layout class="mb-0">
    <x-slot:title>Buat Pengeluaran</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('expense.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Pengeluaran</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Pengeluaran
        </div>
    </x-slot:header>

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col gap-4">
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

    <form action="{{ route('expense.store') }}" method="POST"
        class="mt-10 rounded-xl grid grid-cols-2 gap-8">
        @csrf

        <div class="col-span-1 flex flex-col gap-4">
            {{-- Tanggal --}}
            <x-textfield type="datetime-local" name="date" :value="old('date', now())" classCont="mb-2">
                Waktu
            </x-textfield>

            {{-- Sumber --}}
            <x-dropdown-toggle class="mb-20" name="source" :items="['Teh Botol Sosro', 'Panther', 'Milku']">Sumber</x-dropdown-toggle>

            {{-- Total --}}
            <x-textfield-price name="price" :value="old('price')">Total</x-textfield-price>
        </div>

    </form>
</x-layout>
