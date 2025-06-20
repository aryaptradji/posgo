<x-layout-main>
    <x-slot:title>Detail Pemesan</x-slot:title>

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

    <form action="{{ route('pos-menu.checkout.address.store', $order) }}" method="POST" enctype="multipart/form-data"
        class="flex flex-grow min-h-0 gap-6 mx-14 mt-32" x-data="{
            phone: @js(old('phone', $user->phone_number)),
            city: '{{ old('city', $citySlug) }}',
            district: '{{ old('district', $districtSlug) }}',
            sub_district: '{{ old('sub_district', $subDistrictSlug) }}',
            address: @js(old('address', optional($user->address)->street)),
            rt: @js(old('rt', optional(optional($user->address)->neighborhood)->rt)),
            rw: @js(old('rw', optional(optional($user->address)->neighborhood)->rw)),
            postalCode: @js(old('postal_code', optional(optional($user->address)->neighborhood)->postal_code)),
            phoneError: '',
            addressError: '',
            rtError: '',
            rwError: '',
            postalCodeError: '',
            phoneServerError: '{{ $errors->has('phone') }}',
            addressServerError: '{{ $errors->has('address') }}',
            rtServerError: '{{ $errors->has('rt') }}',
            rwServerError: '{{ $errors->has('rw') }}',
            postalCodeServerError: '{{ $errors->has('postal_code') }}',
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
            }
        }">
        @csrf
        <!-- Detail Pesanan -->
        <div class="w-3/4 shadow-outer py-6 rounded-xl flex flex-col">
            <div class="text-2xl px-8 font-bold pb-4 border-b border-tertiary-title-line">Detail Pemesan</div>
            <div class="flex px-8 gap-36 mt-6">
                <div class="flex flex-col gap-8 flex-1">
                    {{-- Kota --}}
                    <x-dropdown-search :errorClass="$errors->has('city') ? 'border-[3.5px] border-danger focus:border-danger' : 'border-0'" name="city" :items="$cities->map(fn($c) => ['slug' => $c->slug, 'name' => $c->name])->toArray()" :value="$citySlug ?? 'Pilih Salah Satu'">
                        Kota
                    </x-dropdown-search>
                    @error('city')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="$errors->has('city')">{{ $message }}</x-inline-error-message>
                    @enderror
                    <input type="hidden" name="city" x-model="city">

                    {{-- Kecamatan --}}
                    <x-dropdown-search :errorClass="$errors->has('district')
                        ? 'border-[3.5px] border-danger focus:border-danger'
                        : 'border-0'" name="district" :items="$districts->map(fn($d) => ['slug' => $d->slug, 'name' => $d->name])->toArray()" :value="$districtSlug ?? 'Pilih Salah Satu'">
                        Kecamatan
                    </x-dropdown-search>
                    @error('district')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="$errors->has('district')">{{ $message }}</x-inline-error-message>
                    @enderror
                    <input type="hidden" name="district" x-model="district">

                    {{-- Kelurahan --}}
                    <x-dropdown-search :errorClass="$errors->has('sub_district')
                        ? 'border-[3.5px] border-danger focus:border-danger'
                        : 'border-0'" name="sub_district" :items="$subDistricts->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])->toArray()" :value="$subDistrictSlug ?? 'Pilih Salah Satu'">
                        Kelurahan
                    </x-dropdown-search>
                    @error('sub_district')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="$errors->has('sub_district')">{{ $message }}</x-inline-error-message>
                    @enderror
                    <input type="hidden" name="sub_district" x-model="sub_district">

                    {{-- Alamat --}}
                    <x-textfield x-model="address" x-on:input="validateAddress()" type="text" name="address"
                        placeholder="Masukkan alamat dan nomor rumah . . ." class="focus:border-[3.5px]"
                        x-bind:class="addressError || addressServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                            'focus:border-primary'">Alamat</x-textfield>
                    <x-inline-error-message class="mb-3 -mt-6" x-show="addressError"
                        x-text="addressError"></x-inline-error-message>
                    @error('address')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="addressServerError">{{ $message }}</x-inline-error-message>
                    @enderror
                </div>
                <div class="flex flex-col gap-8 flex-1">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- RT --}}
                        <div>
                            <x-textfield x-model="rt" x-on:input="validateRT()" class="focus:border-[3.5px]"
                                x-bind:class="rtError || rtServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                    'focus:border-primary'"
                                type="text" name="rt" placeholder="ex: 001">RT</x-textfield>
                            <x-inline-error-message class="mt-2" x-show="rtError"
                                x-text="rtError"></x-inline-error-message>
                            @error('rt')
                                <x-inline-error-message class="mt-2"
                                    x-show="rtServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>

                        {{-- RW --}}
                        <div>
                            <x-textfield x-model="rw" x-on:input="validateRW()" class="focus:border-[3.5px]"
                                x-bind:class="rwError || rwServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                    'focus:border-primary'"
                                type="text" name="rw" placeholder="ex: 002">RW</x-textfield>
                            <x-inline-error-message class="mt-2" x-show="rwError"
                                x-text="rwError"></x-inline-error-message>
                            @error('rw')
                                <x-inline-error-message class="mt-2"
                                    x-show="rwServerError">{{ $message }}</x-inline-error-message>
                            @enderror
                        </div>
                    </div>

                    {{-- Kode Pos --}}
                    <x-textfield x-model="postalCode" x-on:input="validatePostalCode()" class="focus:border-[3.5px]"
                        x-bind:class="postalCodeError || postalCodeServerError ?
                            'border-[3.5px] border-danger focus:border-danger' : 'focus:border-primary'"
                        type="text" name="postal_code" placeholder="ex: 12123">Kode Pos</x-textfield>
                    <x-inline-error-message class="mb-3 -mt-6" x-show="postalCodeError"
                        x-text="postalCodeError"></x-inline-error-message>
                    @error('postal_code')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="postalCodeServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    {{-- No Telepon --}}
                    <x-textfield x-model="phone" x-on:input="validatePhone()" type="text" name="phone"
                        placeholder="08xxxxxxxxx" class="focus:border-[3.5px]"
                        x-bind:class="phoneError || phoneServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                            'focus:border-primary'">Nomor
                        Telepon</x-textfield>
                    <x-inline-error-message class="mb-3 -mt-6" x-show="phoneError"
                        x-text="phoneError"></x-inline-error-message>
                    @error('phone')
                        <x-inline-error-message class="mb-3 -mt-6"
                            x-show="phoneServerError">{{ $message }}</x-inline-error-message>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Keranjang -->
        <div class="flex flex-col flex-grow h-full shadow-outer py-6 px-8 rounded-xl w-[400px]">
            <div class="text-2xl font-bold mb-6">Keranjang</div>

            <!-- List Item scrollable -->
            <div class="flex flex-col flex-grow overflow-y-auto min-h-0">
                @foreach ($order->items as $item)
                    <div class="flex items-start gap-6 mt-4 w-full">
                        <div
                            class="flex items-center justify-center bg-tertiary-500/30 h-20 p-3 aspect-square object-contain rounded-xl">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="max-h-16">
                        </div>
                        <div class="flex h-full flex-col gap-4 justify-between flex-grow">
                            <div class="flex flex-col">
                                <span class="font-bold">{{ $item->product->name }}</span>
                                <span class="text-tertiary-title text-xs">{{ $item->product->pcs }} pcs</span>
                            </div>
                            <div class="flex justify-between gap-16 min-w-fit">
                                <span class="font-semibold text-tertiary-500">{{ $item->qty }}x</span>
                                <span class="font-semibold text-primary">Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="pt-6">
                <hr class="w-full h-[2px] my-6 bg-tertiary-table-line rounded-full border-0">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-tertiary-500/80 text-lg">Total</span>
                    <span class="font-bold text-primary text-lg">Rp
                        {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Bayar -->
            <div class="flex gap-2">
                {{-- Non-Tunai --}}
                <x-button-sm type="submit" name="payment_method" value="non-tunai"
                    class="bg-primary shadow-outer-sidebar-primary text-white w-full py-2 px-6 mt-auto text-xs">
                    Non-Tunai
                </x-button-sm>

                {{-- Tunai --}}
                <x-button-sm type="submit" name="payment_method" value="tunai"
                    class="bg-secondary-purple shadow-outer-sidebar-secondary text-white w-full py-2 px-6 mt-auto text-xs">
                    Tunai
                </x-button-sm>
            </div>
        </div>
    </form>
</x-layout-main>
