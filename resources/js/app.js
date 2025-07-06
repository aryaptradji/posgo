import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import imageCropper from './components/imageCropper';

window.Alpine = Alpine;
window.imageCropper = imageCropper;

Alpine.start();
