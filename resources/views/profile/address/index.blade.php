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
                <a href="{{ route('customer.home') }}"
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
        <div class="w-full pe-20 text-center">
            <div class="flex flex-col-2 justify-center gap-36" x-data="{
                address: @js(old('address', $user->address->street)),
                rt: @js(old('rt', $user->address->neighborhood->rt)),
                rw: @js(old('rw', $user->address->neighborhood->rw)),
                postalCode: @js(old('postal_code', $user->address->neighborhood->postal_code)),
                addressError: '',
                rtError: '',
                rwError: '',
                postalCodeError: '',
                addressServerError: '{{ $errors->has('address') }}',
                rtServerError: '{{ $errors->has('rt') }}',
                rwServerError: '{{ $errors->has('rw') }}',
                postalCodeServerError: '{{ $errors->has('postal_code') }}',
                showModalAddress: false,
                showModalRT: false,
                showModalRW: false,
                showModalPostalCode: false,
                showModalDistrict: false,
                showModalSubDistrict: false,
                showModalCity: false,
                validateAddress() {
                    this.addressError = '';

                    if (this.address == '') {
                        this.addressError = 'Alamat wajib diisi';
                    }
                    if (this.address !== '') {
                        this.addressServerError = '';
                    }
                },
                validateRT() {
                    this.rtError = '';

                    if (this.rt == '') {
                        this.rtError = 'Nomor RT wajib diisi';
                    } else if (!/^[0-9A-Za-z]*$/.test(this.rt)) {
                        this.rtError = 'Nomor RT tidak boleh mengandung karakter khusus';
                    } else if (/^[A-Za-z]$/.test(this.rt)) {
                        this.rtError = 'Nomor RT tidak boleh mengandung huruf';
                    } else if (!/^[0-9]{3}$/.test(this.rt)) {
                        this.rtError = 'Nomor RT harus mengandung angka 3 digit';
                    }
                    if (this.rt) {
                        this.rtServerError = '';
                    }
                },
                validateRW() {
                    this.rwError = '';

                    if (this.rw == '') {
                        this.rwError = 'Nomor RW wajib diisi';
                    } else if (!/^[0-9A-Za-z]*$/.test(this.rw)) {
                        this.rwError = 'Nomor RW tidak boleh mengandung karakter khusus';
                    } else if (/^[A-Za-z]$/.test(this.rw)) {
                        this.rwError = 'Nomor RW tidak boleh mengandung huruf';
                    } else if (!/^[0-9]{3}$/.test(this.rw)) {
                        this.rwError = 'RW harus mengandung angka 3 digit';
                    }
                    if (this.rw) {
                        this.rwServerError = '';
                    }
                },
                validatePostalCode() {
                    this.postalCodeError = '';

                    if (this.postalCode == '') {
                        this.postalCodeError = 'Kode pos wajib diisi';
                    } else if (!/^[0-9A-Za-z]*$/.test(this.postalCode)) {
                        this.postalCodeError = 'Kode pos tidak boleh mengandung karakter khusus';
                    } else if (/^[A-Za-z]$/.test(this.postalCode)) {
                        this.postalCodeError = 'Kode pos tidak boleh mengandung huruf';
                    } else if (!/^[0-9]{5}$/.test(this.postalCode)) {
                        this.postalCodeError = 'Kode pos harus mengandung angka 5 digit';
                    }
                    if (this.postalCode !== '') {
                        this.postalCodeServerError = '';
                    }
                },
                closeModal() {
                    this.address = '{{ $user->address->street }}';
                    this.rt = '{{ $user->address->neighborhood->rt }}';
                    this.rw = '{{ $user->address->neighborhood->rw }}';
                    this.postalCode = '{{ $user->address->neighborhood->postal_code }}';
                    this.addressError = '';
                    this.rtError = '';
                    this.rwError = '';
                    this.postalCodeError = '';
                    this.addressServerError = '';
                    this.rtServerError = '';
                    this.rwServerError = '';
                    this.postalCodeServerError = '';
                }
            }">
                <div class="flex flex-col gap-8">
                    {{-- Alamat --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">Alamat</span>
                            <span>{{ $user->address->street }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>

                    {{-- RT --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">RT</span>
                            <span>{{ $user->address->neighborhood->rt }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>

                    {{-- RW --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">RW</span>
                            <span>{{ $user->address->neighborhood->rw }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>

                    {{-- Kode Pos --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">Kode Pos</span>
                            <span>{{ $user->address->neighborhood->postal_code }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>
                </div>
                <div class="flex flex-col gap-8">
                    {{-- Kota --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">Kota</span>
                            <span>{{ $user->address->neighborhood->subDistrict->district->city->name }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>

                    {{-- Kelurahan --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">Kelurahan</span>
                            <span>{{ $user->address->neighborhood->subDistrict->name }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>

                    {{-- Kecamatan --}}
                    <a href="{{ route('profile.address.edit', $user) }}"
                        class="group px-6 py-4 w-80 flex justify-between items-center rounded-2xl shadow-outer">
                        <div class="flex flex-col items-start gap-1">
                            <span class="font-bold">Kecamatan</span>
                            <span>{{ $user->address->neighborhood->subDistrict->district->name }}</span>
                        </div>
                        <x-icons.arrow-nav variant="gradient"
                            class="-rotate-90 transition-all group-hover:scale-150 group-active:scale-90 duration-300" />
                    </a>
                </div>
            </div>
            <x-button-sm class="w-fit mt-10 px-7 bg-gradient-to-tr from-primary to-secondary-purple text-white">
                <a href="{{ route('profile.address.edit', $user) }}">Ubah</a>
            </x-button-sm>
        </div>
    </div>
</x-layout-main>
