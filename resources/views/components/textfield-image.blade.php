@props([
    'name' => 'image',
    'uploadClass' => null,
    'previewClass' => null,
    'initialImageUrl' => null,
    'initialFileName' => null,
    'initialFileSize' => null,
])

<div {{ $attributes->merge(['class' => 'col-span-1 flex flex-col gap-4']) }} x-data="imageUploader('{{ $initialImageUrl ?? '' }}', '{{ $initialFileName ?? '' }}', '{{ $initialFileSize ?? '' }}')">
    <label class="block font-bold">{{ $slot }}</label>

    <!-- Upload Area -->
    <template x-if="!fileName">
        <div @dragover.prevent @dragenter.prevent @drop.prevent="handleDrop($event)"
            class="h-full border-2 border-dashed border-gray-300 bg-tertiary rounded-lg shadow-outer flex flex-col justify-center items-center text-sm font-semibold {{ $uploadClass }}">
            <span>Drag & Drop Your Files or <label for="{{ $name }}"
                    class="inline-block bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:opacity-75 hover:scale-95 active:scale-75 cursor-pointer">Browse</label></span>
        </div>
    </template>

    <input type="file" name="{{ $name }}" id="{{ $name }}" class="hidden" @change="handleFile($event)">

    <!-- Preview Area -->
    <template x-if="fileName">
        <div
            class="relative bg-gradient-to-b from-green-400 to-white p-4 rounded-xl h-full shadow-md flex flex-col justify-center items-center {{ $previewClass }}">
            <div class="absolute top-4 left-6 text-left">

                <p class="text-sm font-bold text-white" x-show="fromUpload === true" x-text="fileName"></p>
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
            <img :src="imageUrl" alt="Preview" class="max-h-32 object-contain mt-4 drop-shadow-md">
        </div>
    </template>
</div>

<script>
    function imageUploader(initialUrl = '', initialName = '', initialSize = '') {
        const fromUpload = initialName === '' ? false : null;

        return {
            fileName: initialName,
            fileSize: initialSize,
            imageUrl: initialUrl,
            fromUpload: fromUpload,
            handleFile(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    this.fileName = file.name;
                    this.fileSize = Math.round(file.size / 1024);
                    this.imageUrl = URL.createObjectURL(file);
                    this.fromUpload = true;
                }
            },
            handleDrop(event) {
                const droppedFile = event.dataTransfer.files[0];
                if (droppedFile && droppedFile.type.startsWith('image/')) {
                    this.fileName = droppedFile.name;
                    this.fileSize = Math.round(droppedFile.size / 1024);
                    this.imageUrl = URL.createObjectURL(droppedFile);
                    document.getElementById('{{ $name }}').files = event.dataTransfer.files;
                    this.fromUpload = true;
                }
            },
            reset() {
                this.fileName = '';
                this.fileSize = '';
                this.imageUrl = '';
                this.fromUpload = false;
                document.getElementById('{{ $name }}').value = '';
            }
        }
    }
</script>
