<x-layout>
    <x-slot:title>Buat Produk</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('product.index') }}" class="font-semiboldtransition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Produk</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300"/>
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Produk
        </div>
    </x-slot:header>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 rounded-xl px-8 py-10 grid grid-cols-2 gap-8">
        @csrf
        <div class="col-span-1 flex flex-col gap-4">
            <div>
                <label class="block font-semibold mb-1">Nama Produk<span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Masukkan nama produk disini ..." class="w-full px-4 py-3 border-none rounded-lg shadow-outer focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Stok<span class="text-red-500">*</span></label>
                    <input type="number" name="stock" min="0" value="0" class="w-full px-4 py-3 border-none rounded-lg shadow-outer focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Pcs<span class="text-red-500">*</span></label>
                    <input type="number" name="pcs" min="0" value="0" class="w-full px-4 py-3 border-none rounded-lg shadow-outer focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga<span class="text-red-500">*</span></label>
                <input type="number" name="price" placeholder="Masukkan harga produk disini ..." class="w-full px-4 py-3 border-none rounded-lg shadow-outer focus:outline-none">
            </div>
        </div>

        <div class="col-span-1 flex flex-col gap-4">
            <div>
                <label class="block font-semibold mb-1">Gambar<span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 bg-white rounded-lg shadow-md h-48 flex flex-col justify-center items-center text-sm text-gray-500">
                    <span>Drag & Drop Your Files or <label for="image" class="text-purple-500 hover:underline cursor-pointer">Browse</label></span>
                    <input type="file" name="image" id="image" class="hidden">
                </div>
            </div>
        </div>

        <div class="col-span-2 flex justify-center gap-6 mt-8">
            <a href="{{ route('product.index') }}" class="px-6 py-2 bg-gray-300 text-black font-semibold rounded-full hover:scale-105 transition-all">BATAL</a>
            <button type="submit" class="px-6 py-2 bg-purple-300 text-white font-semibold rounded-full hover:scale-105 transition-all">BUAT</button>
        </div>
    </form>
</x-layout>
