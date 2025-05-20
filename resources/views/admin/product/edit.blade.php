<x-layout>
    <x-slot:title>Ubah Produk</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('product.index') }}"
                class="font-semibold transition-all duration-300 hover:text-primary hover:scale-110 active:scale-90">Produk</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Ubah</span>
        </div>
        <div>
            Ubah Produk
        </div>
    </x-slot:header>

    <!-- Toast Error -->
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

    <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data"
        class="mt-10 rounded-xl grid grid-cols-2 gap-8">
        @csrf
        @method('PUT')
        <div class="col-span-1 flex flex-col gap-4" x-data="{
            raw: '0',
            name: '{{ old('name', $product->name) }}',
            nameError: '',
            nameServerError: {{ $errors->has('name') ? 'true' : 'false' }},
            validateName() {
                this.nameError = ''; // Reset error message

                const specialChar = /^[a-zA-Z0-9.() ]+$/;

                if (this.name == false) {
                    this.nameError = 'Nama wajib diisi';
                } else if (this.name.length < 3) {
                    this.nameError = 'Huruf minimal berjumlah 3';
                } else if (!specialChar.test(this.name)) {
                    this.nameError = 'Tidak boleh mengandung karakter khusus';
                }
                if (this.name !== '') {
                    this.nameServerError = false;
                }
            }
        }">
            <x-textfield classCont="mb-2"
                x-model="name" x-on:input="validateName()" x-bind:class="nameError || nameServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring focus:ring-primary'"
                class="focus:ring"
                type="text" name="name" placeholder="Masukkan nama produk disini . . ."
                value="{{ old('name', $product->name) }}">Nama Produk</x-textfield>
            <x-inline-error-message class="mb-2 -mt-2" x-show="nameError" x-text="nameError"></x-inline-error-message>
            @error('name')
                <x-inline-error-message class="mb-2 -mt-2" x-show="nameServerError">{{ $message }}</x-inline-error-message>
            @enderror

            <div class="grid grid-cols-2 gap-4 mb-2">
                <x-textfield class="focus:ring focus:ring-primary" type="number" name="stock" min="0"
                    placeholder="0" :value="old('stock', $product->stock)" oninput="this.value = Math.max(0, this.value)">Stok</x-textfield>
                <x-textfield class="focus:ring focus:ring-primary" type="number" name="pcs" min="0"
                    placeholder="0" :value="old('pcs', $product->pcs)" oninput="this.value = Math.max(0, this.value)">Pcs</x-textfield>
            </div>

            <x-textfield-price class="focus:ring focus:ring-primary" name="price" :value="old('price', $product->price)">Harga</x-textfield-price>
        </div>

        <x-textfield-image
            x-init="console.log('Preview image:', imageUrl)"
            name="image"
            initial-image-url="{{ asset('storage/' . $product->image) }}"
            initial-file-name="{{ basename($product->image) }}"
            initialFileSize="{{ $sizeInKB }}"
        >
            Gambar
        </x-textfield-image>

        <div class="col-span-2 flex justify-center gap-6 mt-3">
            <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                <a href="{{ route('product.index') }}">Batal</a>
            </x-button-sm>
            <x-button-sm type="submit" class="w-fit px-7 text-primary bg-primary/20">Simpan</x-button-sm>
        </div>
    </form>
</x-layout>
