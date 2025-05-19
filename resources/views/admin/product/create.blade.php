<x-layout class="pb-0">
    <x-slot:title>Buat Produk</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('product.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Produk</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Produk
        </div>
    </x-slot:header>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
        class="mt-12 rounded-xl grid grid-cols-2 gap-8">
        @csrf
        <div class="col-span-1 flex flex-col gap-4" x-data="{
            raw: '0',
            formatRupiah(val) {
                val = val.replace(/^0+/, '').replace(/[^\d]/g, '');
                if (!val) return 'Rp 0';
                let number_string = val.toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return 'Rp ' + rupiah;
            }
        }">
            <x-textfield classCont="mb-2" class="focus:ring focus:ring-primary" type="text" name="name"
                placeholder="Masukkan nama produk disini . . ." value="{{ old('name') }}">Nama Produk</x-textfield>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <x-textfield class="focus:ring focus:ring-primary" type="number" name="stock" min="0"
                    placeholder="0" value="0" oninput="this.value = Math.max(0, this.value)">Stok</x-textfield>
                <x-textfield class="focus:ring focus:ring-primary" type="number" name="pcs" min="0"
                    placeholder="0" value="0" oninput="this.value = Math.max(0, this.value)">Pcs</x-textfield>
            </div>

            <x-textfield type="text" class="focus:ring focus:ring-primary" x-ref="formatted"
                @input="$nextTick(() => {
                    raw = $el.value.replace(/[^\d]/g, '').replace(/^0+/, '');
                    $el.value = formatRupiah(raw);
                })"
                x-bind:value="formatRupiah(raw)" inputmode="numeric">
                Harga
            </x-textfield>

            <input type="hidden" name="price" :value="raw">
        </div>


        <div x-data="imageUploader()" class="col-span-1 flex flex-col gap-4">
            <label class="block font-bold">Gambar</label>

            <!-- Upload Area -->
            <template x-if="!fileName">
                <div
                    @dragover.prevent
                    @dragenter.prevent
                    @drop.prevent="handleDrop($event)"
                    class="h-full border-2 border-dashed border-gray-300 bg-tertiary rounded-lg shadow-outer flex flex-col justify-center items-center text-sm font-semibold">
                    <span>Drag & Drop Your Files or <label for="image"
                            class="inline-block bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:opacity-75 hover:scale-95 active:scale-75 cursor-pointer">Browse</label></span>
                    <input type="file" name="image" id="image" class="hidden" @change="handleFile($event)" value="{{ old('image') }}">
                </div>
            </template>

            <!-- Preview Area -->
            <template x-if="fileName">
                <div
                    class="relative bg-gradient-to-b from-green-400 to-white p-4 rounded-xl h-full shadow-md flex flex-col justify-center items-center">
                    <div class="absolute top-4 left-6 text-left">
                        <p class="text-sm font-bold text-white" x-text="fileName"></p>
                        <p class="text-xs text-white opacity-80" x-text="fileSize + ' KB'"></p>
                    </div>
                    <div class="absolute top-4 right-14 text-right">
                        <p class="text-sm font-semibold text-white">Upload complete</p>
                        <p class="text-xs text-white opacity-80">tap to undo</p>
                    </div>
                    <button @click="reset()"
                        class="absolute top-4 right-5 aspect-square rounded-full transition-all hover:scale-110 active:scale-90 bg-white/70 text-xl font-bold">
                        <x-icons.close />
                    </button>
                    <img :src="imageUrl" alt="Preview" class="w-32 mt-4 drop-shadow-md">
                </div>
            </template>
        </div>

        <div class="col-span-2 flex justify-center gap-6 mt-5">
            <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                <a href="{{ route('product.index') }}">Batal</a>
            </x-button-sm>
            <x-button-sm type="submit" class="w-fit px-7 text-primary bg-primary/20">Simpan</x-button-sm>
        </div>
    </form>

    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25" slotClass="text-danger" :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed/>
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif

    <script>
        function imageUploader() {
            return {
                fileName: '',
                fileSize: '',
                imageUrl: '',
                handleFile(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        this.fileName = file.name;
                        this.fileSize = Math.round(file.size / 1024);
                        this.imageUrl = URL.createObjectURL(file);
                    }
                },
                handleDrop(event) {
                    const droppedFile = event.dataTransfer.files[0];
                    if (droppedFile && droppedFile.type.startsWith('image/')) {
                        this.fileName = droppedFile.name;
                        this.fileSize = Math.round(droppedFile.size / 1024);
                        this.imageUrl = URL.createObjectURL(droppedFile);
                        // Juga set ke input file agar ikut terkirim saat submit
                        document.getElementById('image').files = event.dataTransfer.files;
                    }
                },
                reset() {
                    this.fileName = '';
                    this.fileSize = '';
                    this.imageUrl = '';
                    document.getElementById('image').value = '';
                }
            }
        }
    </script>
</x-layout>
