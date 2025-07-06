import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export default function imageCropper(initialUrl, initialsText) {
    return {
        showModalPhoto: false,
        cropping: false,
        previewUrl: initialUrl,       // foto awal
        tempPreviewUrl: '',           // foto sementara saat crop
        initials: initialsText,
        hasChange: false,
        cropper: null,

        handleFile(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.tempPreviewUrl = URL.createObjectURL(file);
                this.startCrop();
            }
            e.target.value = null;
        },

        startCrop() {
            this.cropping = true;
            this.$nextTick(() => {
                const img = document.getElementById('cropperImage');
                if (this.cropper) this.cropper.destroy();
                this.cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                    preview: '.cropper-live-preview' // <-- Tambahkan ini
                });
            });
        },

        startCropExisting() {
            this.tempPreviewUrl = this.previewUrl;
            this.startCrop();
        },

        cancelCrop() {
            if (this.cropper) this.cropper.destroy();
            this.cropper = null;
            this.cropping = false;
            document.getElementById('photo').value = null;
        },

        saveCrop() {
            if (this.cropper) {
                const canvas = this.cropper.getCroppedCanvas();
                this.previewUrl = canvas.toDataURL('image/png');
                this.hasChange = true;    // tandai udah ada perubahan
                this.cropper.destroy();
                this.cropper = null;
                this.cropping = false;
                document.getElementById('photo').value = null;
            }
        },

        removePhoto() {
            this.previewUrl = '';
            this.tempPreviewUrl = '';
            this.hasChange = true;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            document.getElementById('photo').value = null;
        }
    }
}
