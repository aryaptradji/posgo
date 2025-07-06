<x-layout-main>
    <x-slot:title>Profil</x-slot:title>

    {{-- Toast Error --}}
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
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    <div class="flex flex-col-2 px-14 pt-32 pb-8 gap-44 min-h-screen">
        {{-- Sidebar --}}
        <div class="py-6 w-2/6 rounded-2xl shadow-outer">
            <div class="flex items-center gap-4 px-8 mb-8">
                <a href="{{ route('customer.home') }}"
                    class="transition-all hover:scale-150 active:scale-90 group duration-300">
                    <x-icons.arrow-nav class="rotate-90" width="20" height="20" variant="gradient" />
                </a>
                <span class="text-2xl font-bold">Profil</span>
            </div>

            <div>
                @php
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
                @endphp

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
        <div class="w-full pe-16">
            @php
                $user = Auth::user();
                $parts = explode(' ', $user->name);
                $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
            @endphp

            <div class="flex items-center justify-center mb-10 h-44 p-2 gap-6">
                @if ($user->photo_url)
                    <img src="{{ $user->photo_url }}"
                        class="rounded-full shadow-outer h-44 w-44 aspect-square object-cover">
                @else
                    <div
                        class="bg-tertiary-title-line shadow-outer text-tertiary-title font-semibold rounded-full w-44 h-44 flex items-center justify-center text-7xl">
                        {{ $initials }}
                    </div>
                @endif

                <form action="{{ route('profile.account.photo', $user) }}" method="POST" enctype="multipart/form-data">
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
                                            :src="tempPreviewUrl" class="max-w-full rounded">
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
            <div class="flex flex-col-2 justify-between" x-data="{ showModalName: false, showModalPhone: false, showModalEmail: false, showModalPassword: false }">
                <div class="flex flex-col gap-12">
                    {{-- Nama --}}
                    <button type="button" @click="showModalName = true"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col gap-1">
                            <span class="font-bold">Nama</span>
                            <span>{{ $user->name }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </button>

                    <form action="#" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <x-modal show="showModalName">
                            <x-slot:title>
                                <div class="w-full flex justify-between">
                                    <div class="flex">
                                        <x-icons.delivery class="mr-3 text-secondary-blue" />
                                        <h2 class="text-lg font-bold">Kirim Pesanan</h2>
                                    </div>
                                    <button
                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                        type="button" @click="showModalName = false">
                                        <x-icons.close />
                                    </button>
                                </div>
                            </x-slot:title>

                            <div class="px-8 mb-8 w-[70vh] text-start">

                            </div>
                            <x-slot:action>
                                <div class="flex mr-2 gap-3">
                                    <button type="button" @click="showModalName = false"
                                        class="px-6 py-3 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-3 bg-secondary-blue text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        Kirim
                                    </button>
                                </div>
                            </x-slot:action>
                        </x-modal>
                    </form>

                    {{-- Nomor Telepon --}}
                    <div class="px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col gap-1">
                            <span class="font-bold">Nomor Telepon</span>
                            <span>{{ $user->phone_number }}</span>
                        </div>
                        <x-icons.arrow-nav class="-rotate-90" />
                    </div>
                </div>
                <div class="flex flex-col gap-12">
                    {{-- Email --}}
                    <div class="flex flex-col gap-12">
                        <div class="px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold">Email</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <x-icons.arrow-nav class="-rotate-90" />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-12">
                        <div class="px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold">Password</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <x-icons.arrow-nav class="-rotate-90" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-main>
