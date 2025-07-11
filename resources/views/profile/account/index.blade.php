<x-layout-main>
    <x-slot:title>Profil</x-slot:title>

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25" slotClass="text-danger"
                    :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed />
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif
    @if (session('error'))
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
            <x-toast id="toast-failed" iconClass="text-danger bg-danger/25" slotClass="text-danger" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-failed />
                </x-slot:icon>
                {{ session('error') }}
            </x-toast>
        </div>
    @endif

    {{-- Toast Success --}}
    @if (session('success'))
        <div class="fixed top-16 right-10 z-50 flex flex-col justify-end gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success"
                :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    <div class="flex flex-col-2 px-14 pt-32 gap-44 h-[92vh]">
        @php
            $user = Auth::user();
            $parts = explode(' ', $user->name);
            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

            if ($user->role === 'customer') {
                $menus = [
                    [
                        'name' => 'Akun',
                        'active' => request()->routeIs('profile.account'),
                        'icon' => view('components.icons.account')->render(),
                        'route' => route('profile.account'),
                    ],
                    [
                        'name' => 'Alamat',
                        'active' => request()->routeIs('profile.address'),
                        'icon' => view('components.icons.address')->render(),
                        'route' => route('profile.address'),
                    ],
                ];
            } else {
                $menus = [
                    [
                        'name' => 'Akun',
                        'active' => request()->routeIs('profile.account'),
                        'icon' => view('components.icons.account')->render(),
                        'route' => route('profile.account'),
                    ],
                ];
            }
        @endphp

        {{-- Sidebar --}}
        <div class="py-6 w-2/6 rounded-2xl shadow-outer">
            <div class="flex items-center gap-4 px-8 mb-8">
                <a href="{{ $user->role === 'customer' ? route('customer.home') : route('pos-menu') }}"
                    class="transition-all hover:scale-150 active:scale-90 group duration-300">
                    <x-icons.arrow-nav class="rotate-90" width="20" height="20" variant="gradient" />
                </a>
                <span class="text-2xl font-bold">Profil</span>
            </div>

            <div>
                @foreach ($menus as $menu)
                    <a href="{{ $menu['route'] }}"
                        class="flex gap-6 py-5 mb-3 ml-8 rounded-sm transition-all duration-300 {{ $menu['active'] ? 'text-primary border-r-[5px] border-primary bg-gradient-to-r from-primary/0 to-primary/15' : 'text-black hover:border-r-[5px] hover:border-primary' }}">
                        {!! $menu['icon'] !!}
                        <span class="font-semibold text-xl">{{ $menu['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full pe-20">
            <div class="flex items-center justify-center mb-16 h-44 p-2 gap-6">
                @if ($user->photo_url)
                    <img src="{{ $user->photo_url }}"
                        class="rounded-full shadow-outer h-44 w-44 aspect-square object-cover">
                @else
                    <div
                        class="bg-tertiary-title-line shadow-outer text-tertiary-title font-semibold rounded-full w-44 h-44 flex items-center justify-center text-7xl">
                        {{ $initials }}
                    </div>
                @endif

                <form action="{{ route('profile.account.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col gap-4" x-data="imageCropper('{{ $user->photo_url ?? '' }}')">
                        <button type="button" @click="showModalPhoto = true"
                            class="group transition-transform hover:scale-125 active:scale-90">
                            <x-icons.camera-drop variant="gradient" />
                        </button>

                        {{-- Modal Foto --}}
                        <x-modal show="showModalPhoto" actionClass="justify-center pb-2"
                            contClass="max-h-[calc(100vh-8rem)] max-w-[500px] overflow-y-auto">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.camera class="mr-3" width="26" height="26" />
                                        <h2 class="text-lg font-bold">Foto Profil</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalPhoto = false">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="w-full flex justify-center items-center gap-4">
                                {{-- Foto --}}
                                <div class="flex items-center justify-center pe-6">
                                    <template x-if="previewUrl && !cropping">
                                        <img :src="previewUrl" class="rounded-full h-44 w-44 object-cover">
                                    </template>
                                    <template x-if="!previewUrl && !cropping">
                                        <div
                                            class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-44 h-44 flex items-center justify-center text-7xl">
                                            {{ $initials }}
                                        </div>
                                    </template>
                                </div>

                                {{-- Cropping --}}
                                <div x-show="cropping" class="w-full flex gap-8 items-center justify-between">
                                    <div class="flex justify-start items-center"> <img id="cropperImage"
                                            :src="tempPreviewUrl" class="max-h-[60vh] rounded">
                                    </div>

                                    <div class="flex flex-col items-center justify-center gap-6">
                                        <div class="text-center">
                                            <p class="font-semibold text-sm mb-2">Preview :</p>
                                            <div
                                                class="cropper-live-preview rounded-full h-44 w-44 overflow-hidden border border-gray-300">
                                            </div>
                                        </div>
                                        <div class="flex gap-6">
                                            <template x-if="cropping">
                                                <div class="flex gap-2 text-sm">
                                                    <button type="button" @click="cancelCrop"
                                                        class="px-3 py-1 bg-btn-cancel font-semibold rounded-full hover:scale-110 hover:shadow-drop active:scale-90 transition-all duration-200">Batal</button>
                                                    <button type="button" @click="saveCrop"
                                                        class="px-3 py-1 font-semibold bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full hover:scale-110 hover:shadow-drop active:scale-90 transition-all duration-200">Crop</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Foto --}}
                                <div class="flex flex-col gap-4" x-show="!cropping">
                                    <span class="ml-2 text-xs text-tertiary-400 font-semibold">(format .jpg/.jpeg, max.
                                        2 mb)</span>
                                    <label for="photo"
                                        class="w-fit flex items-center gap-2 px-3 py-1 text-sm font-semibold text-warning-200 bg-tertiary-table-line rounded-full cursor-pointer hover:brightness-95">
                                        <x-icons.edit-icon variant="no-shadow" class="mt-1" />
                                        Ubah Foto
                                    </label>
                                    <input type="file" id="photo" class="hidden" @change="handleFile">

                                    <button type="button" @click="removePhoto"
                                        class="w-fit flex items-center gap-2 px-3 py-1 text-sm font-semibold text-danger bg-tertiary-table-line rounded-full hover:brightness-95">
                                        <x-icons.delete-icon variant="no-shadow" class="mt-1" />
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>

                            <x-slot:action>
                                <template x-if="!cropping && hasChange">
                                    <div class="flex gap-2 font-semibold">
                                        <button type="button" @click="showModalPhoto = false"
                                            class="px-4 py-2 bg-btn-cancel rounded-full hover:scale-110 hover:shadow-drop active:scale-90 transition-all duration-200">Tutup</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full hover:scale-110 hover:shadow-drop active:scale-90 transition-all duration-200">Simpan</button>
                                    </div>
                                </template>

                                <template x-if="!cropping && !hasChange && !previewUrl">
                                    <button type="button" @click="showModalPhoto = false"
                                        class="px-4 py-2 font-semibold bg-btn-cancel rounded-full hover:scale-110 hover:shadow-drop active:scale-90 transition-all duration-200">Tutup</button>
                                </template>
                            </x-slot:action>
                        </x-modal>
                        <input type="hidden" name="profile" x-model="previewUrl">
                    </div>
                </form>
            </div>
            <div class="flex flex-col-2 justify-center gap-36" x-data="{
                showModalName: false,
                showModalPhone: false,
                showModalEmail: false,
                showModalPassword: false,
                name: @js(old('name', $user->name)),
                phone: @js(old('phone', $user->phone_number)),
                email: @js(old('email', $user->email)),
                oldPassword: @js(old('old_password')),
                newPassword: @js(old('new_password')),
                confirmPassword: @js(old('old_password')),
                nameError: '',
                phoneError: '',
                emailError: '',
                oldPasswordError: '',
                newPasswordError: '',
                confirmPasswordError: '',
                nameServerError: '{{ $errors->has('name') }}',
                phoneServerError: '{{ $errors->has('phone') }}',
                emailServerError: '{{ $errors->has('email') }}',
                oldPasswordServerError: '{{ $errors->has('old_password') }}',
                newPasswordServerError: '{{ $errors->has('new_password') }}',
                confirmPasswordServerError: '{{ $errors->has('confirm_password') }}',
                validateName() {
                    this.nameError = '';

                    if (this.name == false) {
                        this.nameError = 'Nama wajib diisi';
                    } else if (!/^[a-zA-Z]+[a-zA-Z.\s]*$/.test(this.name)) {
                        this.nameError = 'Nama hanya boleh mengandung huruf'
                    }
                    if (this.name !== '') {
                        this.nameServerError = false;
                    }
                },
                validatePhone() {
                    this.phoneError = '';

                    if (this.phone == '') {
                        this.phoneError = 'Nomor telepon wajib diisi';
                    } else if (!/^08/.test(this.phone)) {
                        this.phoneError = 'Nomor harus berformat 08xxxxxxxxxxx';
                    } else if (!/^[0-9]+$/.test(this.phone)) {
                        this.phoneError = 'Nomor hanya boleh berisi angka';
                    } else if (!/^[0-9]{10,15}$/.test(this.phone)) {
                        this.phoneError = 'Nomor harus berjumlah 10-15 digit';
                    }
                    if (this.phone !== '') {
                        this.phoneServerError = '';
                    }
                },
                validateEmail() {
                    this.emailError = '';

                    if (this.email == false) {
                        this.emailError = 'Email wajib diisi';
                    } else if (!/^[a-zA-Z0-9](\.?[a-zA-Z0-9_]+)*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
                        this.emailError = 'Format email tidak valid';
                    }
                    if (this.email !== '') {
                        this.emailServerError = '';
                    }
                },
                validateOldPassword() {
                    this.oldPasswordError = '';

                    if (!this.oldPassword) {
                        this.oldPasswordError = 'Password lama wajib diisi';
                    }
                    if (this.oldPassword !== '') {
                        this.oldPasswordServerError = '';
                    }
                },
                validateNewPassword() {
                    this.newPasswordError = '';

                    if (this.newPassword == '') {
                        this.newPasswordError = 'Password baru wajib diisi';
                    } else if (this.newPassword.length <= 8) {
                        this.newPasswordError = 'Password baru minimal berjumlah 8 karakter';
                    } else if (!/^(?=.*[A-Z]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password baru setidaknya harus mengandung 1 huruf besar';
                    } else if (!/^(?=.*[0-9]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password baru setidaknya harus mengandung 1 digit angka';
                    } else if (!/^(?=.*[!@#$%^&*._]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password baru setidaknya harus mengandung 1 karakter khusus';
                    } else if (this.newPassword === this.oldPassword) {
                        this.newPasswordError = 'Gunakan password baru yang belum pernah dipakai';
                    }
                    if (this.newPassword !== '') {
                        this.newPasswordServerError = '';
                    }
                },
                validateConfirmPassword() {
                    this.confirmPasswordError = '';

                    if (this.confirmPassword == '') {
                        this.confirmPasswordError = 'Konfirmasi password wajib diisi';
                    } else if (this.confirmPassword !== this.newPassword) {
                        this.confirmPasswordError = 'Isi konfirmasi password harus sama';
                    }
                    if (this.confirmPassword !== '') {
                        this.confirmPasswordServerError = '';
                    }
                },
                closeModal() {
                    this.name = '{{ $user->name }}';
                    this.phone = '{{ $user->phone_number }}';
                    this.email = '{{ $user->email }}';
                    this.nameError = '';
                    this.phoneError = '';
                    this.emailError = '';
                    this.nameServerError = '';
                    this.phoneServerError = '';
                    this.emailServerError = '';
                }
            }">
                <div class="flex flex-col gap-20">
                    {{-- Nama --}}
                    <form action="{{ route('profile.account.name') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <button type="button" @click="showModalName = true"
                            class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col items-start gap-1">
                                <span class="font-bold">Nama</span>
                                <span>{{ $user->name }}</span>
                            </div>
                            <x-icons.arrow-nav variant="gradient"
                                class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                        </button>

                        {{-- Modal Nama --}}
                        <x-modal show="showModalName">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.info-icon class="mr-3" />
                                        <h2 class="text-lg font-bold">Nama</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalName = false; closeModal()">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="px-2 mb-8 w-[70vh] text-start">
                                <x-textfield x-model="name" x-on:input="validateName()" classCont="mb-3"
                                    class="focus:ring"
                                    x-bind:class="nameError || nameServerError ? 'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'"
                                    type="text" name="name"
                                    placeholder="Masukkan nama lengkap disini . . .">Nama</x-textfield>
                                <x-inline-error-message x-show="nameError"
                                    x-text="nameError"></x-inline-error-message>
                                @error('name')
                                    <x-inline-error-message
                                        x-show="nameServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>
                            <x-slot:action>
                                <div class="flex mr-2 gap-3">
                                    <button type="button" @click="showModalName = false; closeModal()"
                                        class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Simpan
                                    </button>
                                </div>
                            </x-slot:action>
                        </x-modal>
                    </form>

                    {{-- Nomor Telepon --}}
                    <form action="{{ route('profile.account.phone') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <button type="button" @click="showModalPhone = true"
                            class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col items-start gap-1">
                                <span class="font-bold">Nomor Telepon</span>
                                <span>{{ $user->phone_number }}</span>
                            </div>
                            <x-icons.arrow-nav variant="gradient"
                                class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                        </button>

                        {{-- Modal Nomor Telepon --}}
                        <x-modal show="showModalPhone">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.info-icon class="mr-3" />
                                        <h2 class="text-lg font-bold">Nomor Telepon</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalPhone = false; closeModal()">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="px-2 mb-8 w-[70vh] text-start">
                                <x-textfield x-model="phone" x-on:input="validatePhone()" type="text"
                                    name="phone" inputmode="numeric" placeholder="08xxxxxxxxx" class="focus:ring"
                                    x-bind:class="phoneError || phoneServerError ? 'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'"
                                    classCont="mb-3">Nomor
                                    Telepon</x-textfield>
                                <x-inline-error-message x-show="phoneError"
                                    x-text="phoneError"></x-inline-error-message>
                                @error('phone')
                                    <x-inline-error-message
                                        x-show="phoneServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>
                            <x-slot:action>
                                <div class="flex mr-2 gap-3">
                                    <button type="button" @click="showModalPhone = false; closeModal()"
                                        class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Simpan
                                    </button>
                                </div>
                            </x-slot:action>
                        </x-modal>
                    </form>
                </div>
                <div class="flex flex-col gap-20">
                    {{-- Email --}}
                    <form action="{{ route('profile.account.email') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <button type="button" @click="showModalEmail = true"
                            class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col items-start gap-1">
                                <span class="font-bold">Email</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <x-icons.arrow-nav variant="gradient"
                                class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                        </button>

                        {{-- Modal Email --}}
                        <x-modal show="showModalEmail">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.info-icon class="mr-3" />
                                        <h2 class="text-lg font-bold">Email</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalEmail = false; closeModal()">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="px-2 mb-8 w-[70vh] text-start">
                                <x-textfield x-model="email" x-on:input="validateEmail()" type="email"
                                    name="email" placeholder="Masukkan email . . ." class="focus:ring"
                                    x-bind:class="emailError || emailServerError ? 'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'"
                                    classCont="mb-3">Email</x-textfield>
                                <x-inline-error-message x-show="emailError"
                                    x-text="emailError"></x-inline-error-message>
                                @error('email')
                                    <x-inline-error-message
                                        x-show="emailServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>
                            <x-slot:action>
                                <div class="flex mr-2 gap-3">
                                    <button type="button" @click="showModalEmail = false; closeModal()"
                                        class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Simpan
                                    </button>
                                </div>
                            </x-slot:action>
                        </x-modal>
                    </form>

                    {{-- Password --}}
                    <form action="{{ route('profile.account.password') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <button type="button" @click="showModalPassword = true"
                            class="group px-6 pt-4 pb-3 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col items-start justify-between gap-2">
                                <span class="font-bold">Password</span>
                                <span>*******</span>
                            </div>
                            <x-icons.arrow-nav variant="gradient"
                                class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                        </button>

                        {{-- Modal Password --}}
                        <x-modal show="showModalPassword">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.info-icon class="mr-3" />
                                        <h2 class="text-lg font-bold">Password</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalPassword = false; closeModal()">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="px-2 mb-8 w-[70vh] text-start">
                                {{-- Old Password --}}
                                <x-textfield-password x-model="oldPassword" x-on:input="validateOldPassword()"
                                    name="old_password" id="old_password" placeholder="Masukkan password lama . . ."
                                    class="focus:ring"
                                    x-bind:class="oldPasswordError || oldPasswordServerError ?
                                        'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'"
                                    classCont="mb-3">Password Lama</x-textfield-password>
                                <x-inline-error-message x-show="oldPasswordError"
                                    x-text="oldPasswordError"></x-inline-error-message>
                                @error('old_password')
                                    <x-inline-error-message
                                        x-show="oldPasswordServerError">{{ $message }}</x-inline-error-message>
                                @enderror

                                {{-- New Password --}}
                                <x-textfield-password x-model="newPassword" x-on:input="validateNewPassword()"
                                    name="new_password" id="new_password" placeholder="Masukkan password baru . . ."
                                    classCont="mb-3 mt-8" class="focus:ring"
                                    x-bind:class="newPasswordError || newPasswordServerError ? 'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'">Password
                                    Baru</x-textfield-password>
                                <x-inline-error-message x-show="newPasswordError"
                                    x-text="newPasswordError"></x-inline-error-message>
                                @error('new_password')
                                    <x-inline-error-message
                                        x-show="newPasswordServerError">{{ $message }}</x-inline-error-message>
                                @enderror

                                {{-- Confirm Password --}}
                                <x-textfield-password x-model="confirmPassword" x-on:input="validateConfirmPassword()"
                                    name="confirm_password" id="confirm_password"
                                    placeholder="Konfirmasi password baru . . ." classCont="mb-3 mt-8"
                                    class="focus:ring"
                                    x-bind:class="confirmPasswordError || confirmPasswordServerError ?
                                        'ring ring-danger focus:ring-danger' :
                                        'focus:ring-primary'">Konfirmasi
                                    Password Baru</x-textfield-password>
                                <x-inline-error-message x-show="confirmPasswordError"
                                    x-text="confirmPasswordError"></x-inline-error-message>
                                @error('confirm_password')
                                    <x-inline-error-message
                                        x-show="confirmPasswordServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>
                            <x-slot:action>
                                <div class="flex mr-2 gap-3">
                                    <button type="button" @click="showModalPassword = false; closeModal()"
                                        class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-br from-primary to-secondary-purple text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Simpan
                                    </button>
                                </div>
                            </x-slot:action>
                        </x-modal>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-main>
