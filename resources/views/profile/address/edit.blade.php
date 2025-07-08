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
                            'active' =>
                                request()->routeIs('profile.address') || request()->routeIs('profile.address.edit'),
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
        <div class="w-full pe-20">
            {{-- Breadcrumb --}}
            <div class="flex mb-6 items-center gap-2 text-sm text-tertiary-title">
                <a href="{{ route('profile.address') }}"
                    class="font-semibold transition-all duration-300 hover:bg-gradient-to-br hover:from-primary hover:to-secondary-purple hover:text-transparent hover:bg-clip-text hover:scale-110 active:scale-90">Alamat</a>
                <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
                <span class="font-semibold">Ubah</span>
            </div>

            <form action="{{ route('profile.address.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-col-2 justify-center gap-24" x-data="{
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

                    <div class="flex flex-col gap-4 w-full">
                        {{-- Kota --}}
                        <div>
                            <x-dropdown-search :errorClass="$errors->has('city') ? 'border-[3.5px] border-danger focus:border-danger' : 'border-0'" name="city" :items="$cities->map(fn($c) => ['slug' => $c->slug, 'name' => $c->name])->toArray()" :value="$citySlug"
                                contClass="mb-2">
                                Kota
                            </x-dropdown-search>
                            @error('city')
                                <x-inline-error-message
                                    x-show="$errors->has('city')">{{ $message }}</x-inline-error-message>
                            @enderror
                            <input type="hidden" name="city" value="{{ request('city') }}">
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <x-dropdown-search :errorClass="$errors->has('district') ? 'border-[3.5px] border-danger focus:border-danger' : 'border-0'" name="district" :items="$districts->map(fn($d) => ['slug' => $d->slug, 'name' => $d->name])->toArray()" :value="$districtSlug"
                                contClass="mb-2">
                                Kecamatan
                            </x-dropdown-search>
                            @error('district')
                                <x-inline-error-message
                                    x-show="$errors->has('district')">{{ $message }}</x-inline-error-message>
                            @enderror
                            <input type="hidden" name="district" value="{{ request('district') }}">
                        </div>

                        {{-- Kelurahan --}}
                        <div>
                            {{-- Kelurahan --}}
                            <x-dropdown-search :errorClass="$errors->has('sub_district') ? 'border-[3.5px] border-danger focus:border-danger' : 'border-0'" name="sub_district" :items="$subDistricts
                                ->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])
                                ->toArray()"
                                :value="$subDistrictSlug ?? 'Pilih Salah Satu'" contClass="mb-2">
                                Kelurahan
                            </x-dropdown-search>
                            @error('sub_district')
                                <x-inline-error-message
                                    x-show="$errors->has('sub_district')">{{ $message }}</x-inline-error-message>
                            @enderror
                            <input type="hidden" name="sub_district" value="{{ request('sub_district') }}">
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <x-textfield x-model="address" x-on:input="validateAddress()" type="text" name="address"
                                placeholder="Masukkan alamat dan nomor rumah . . ." class="focus:ring"
                                x-bind:class="addressError || addressServerError ? 'ring ring-danger focus:ring-danger' :
                                    'focus:ring-primary'"
                                classCont="mb-2">Alamat</x-textfield>
                            <x-inline-error-message x-show="addressError"
                                x-text="addressError"></x-inline-error-message>
                            @error('address')
                                <x-inline-error-message
                                    x-show="addressServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 w-full">
                        {{-- RT --}}
                        <div>
                            <x-textfield x-model="rt" x-on:input="validateRT()" class="focus:ring"
                                x-bind:class="rtError || rtServerError ? 'ring ring-danger focus:ring-danger' :
                                    'focus:ring-primary'"
                                classCont="mb-2" type="text" name="rt" placeholder="ex: 001">RT</x-textfield>
                            <x-inline-error-message x-show="rtError" x-text="rtError"></x-inline-error-message>
                            @error('rt')
                                <x-inline-error-message x-show="rtServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>

                        {{-- RW --}}
                        <div>
                            <x-textfield x-model="rw" x-on:input="validateRW()" class="focus:ring"
                                x-bind:class="rwError || rwServerError ? 'ring ring-danger focus:ring-danger' :
                                    'focus:ring-primary'"
                                classCont="mb-2" type="text" name="rw" placeholder="ex: 002">RW</x-textfield>
                            <x-inline-error-message x-show="rwError" x-text="rwError"></x-inline-error-message>
                            @error('rw')
                                <x-inline-error-message x-show="rwServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>

                        {{-- Kode Pos --}}
                        <div>
                            <x-textfield x-model="postalCode" x-on:input="validatePostalCode()" class="focus:ring"
                                x-bind:class="postalCodeError || postalCodeServerError ?
                                    'ring ring-danger focus:ring-danger' : 'focus:ring-primary'"
                                classCont="mb-2" type="text" name="postal_code" placeholder="ex: 12123">Kode
                                Pos</x-textfield>
                            <x-inline-error-message x-show="postalCodeError"
                                x-text="postalCodeError"></x-inline-error-message>
                            @error('postal_code')
                                <x-inline-error-message
                                    x-show="postalCodeServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>

                        <div class="text-center">
                            <x-button-sm type="submit"
                                class="w-fit mt-10 px-7 bg-gradient-to-tr from-primary to-secondary-purple text-white">
                                Simpan
                            </x-button-sm>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-layout-main>
