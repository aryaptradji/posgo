<x-layout class="mb-0">
    <x-slot:title>Buat Data Supplier</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('supplier.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Supplier</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Data Supplier
        </div>
    </x-slot:header>

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col items-end gap-4">
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

    <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-10 rounded-xl grid grid-cols-2 gap-8"
            x-data="{
                name: @js(old('name')),
                phone: @js(old('phone')),
                email: @js(old('email')),
                fax: @js(old('fax')),
                address: @js(old('address')),
                nameError: '',
                phoneError: '',
                emailError: '',
                addressError: '',
                nameServerError: '{{ $errors->has('name') }}',
                phoneServerError: '{{ $errors->has('phone') }}',
                emailServerError: '{{ $errors->has('email') }}',
                faxServerError: '{{ $errors->has('fax') }}',
                addressServerError: '{{ $errors->has('address') }}',
                validateName() {
                    this.nameError = '';

                    if (this.name == false) {
                        this.nameError = 'Nama wajib diisi';
                    }
                    if (this.name !== '') {
                        this.nameServerError = false;
                    }
                },
                validatePhone() {
                    this.phoneError = '';

                    if (this.phone == false) {
                        this.phoneError = 'Nomor telepon wajib diisi';
                    } else if (!/^08/.test(this.phone)) {
                        this.phoneError = 'Nomor telepon harus berformat 08xxxxxxxxxxx';
                    } else if (!/^[0-9]+$/.test(this.phone)) {
                        this.phoneError = 'Nomor telepon hanya boleh berisi angka';
                    } else if(!/^[0-9]{10,15}$/.test(this.phone)) {
                        this.phoneError = 'Nomor telepon harus berjumlah 10-15 digit';
                    }
                    if (this.phone !== '') {
                        this.phoneServerError = false;
                    }
                },
                validateEmail() {
                    this.emailError = '';

                    if (this.email == false) {
                        this.emailError = 'Email wajib diisi';
                    } else if (!/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
                        this.emailError = 'Format email tidak valid'
                    }
                    if (this.email !== '') {
                        this.emailServerError = false;
                    }
                },
                validateFax() {
                    if (this.fax !== '') {
                        this.faxServerError = false;
                    }
                },
                validateAddress() {
                    this.addressError = '';

                    if (this.address == false) {
                        this.addressError = 'Alamat wajib diisi';
                    }
                    if (this.address !== '') {
                        this.addressServerError = false;
                    }
                }
            }"
        >
            <div class="col-span-1 flex flex-col gap-2">
                {{-- Nama --}}
                <x-textfield x-model="name" x-on:input="validateName()" class="focus:ring" x-bind:class="nameError || nameServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring-primary'" type="text" name="name"
                    placeholder="Masukkan nama supplier disini . . ." classCont="mb-2">Nama</x-textfield>
                <x-inline-error-message class="mb-2 -mt-2" x-show="nameError" x-text="nameError"></x-inline-error-message>
                @error('name')
                    <x-inline-error-message class="mb-2 -mt-2" x-show="nameServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- Telepon --}}
                <x-textfield x-model="phone" x-on:input="validatePhone()" class="focus:ring" x-bind:class="phoneError || phoneServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring-primary'" type="text" name="phone"
                    placeholder="08xxxxxxxxxxx" classCont="mb-2">Telepon</x-textfield>
                <x-inline-error-message class="mb-2 -mt-2" x-show="phoneError" x-text="phoneError"></x-inline-error-message>
                @error('phone')
                    <x-inline-error-message class="mb-2 -mt-2" x-show="phoneServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- Email --}}
                <x-textfield x-model="email" x-on:input="validateEmail()" class="focus:ring" x-bind:class="emailError || emailServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring-primary'" type="email" name="email"
                    placeholder="Masukkan email supplier disini . . ." classCont="mb-2">Email</x-textfield>
                <x-inline-error-message class="mb-2 -mt-2" x-show="emailError" x-text="emailError"></x-inline-error-message>
                @error('email')
                    <x-inline-error-message class="mb-2 -mt-2" x-show="emailServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- Fax --}}
                <x-textfield x-model="fax" x-on:input="validateFax()" class="focus:ring" x-bind:class="faxServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring-primary'" type="text" name="fax"
                    placeholder="Masukkan nomor fax supplier disini . . ." classCont="mb-2">Fax</x-textfield>
                <x-inline-error-message class="mb-2 -mt-2" x-show="faxError" x-text="faxError"></x-inline-error-message>
                @error('fax')
                    <x-inline-error-message class="mb-2 -mt-2" x-show="faxServerError">{{ $message }}</x-inline-error-message>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="flex flex-col gap-10">
                <div>
                    <x-textarea x-model="address" x-on:input="validateAddress()" classCont="mb-2" class="focus:ring" x-bind:class="addressError || addressServerError ? 'focus:ring-danger ring ring-danger' : 'focus:ring-primary'" name="address" placeholder="Masukkan alamat supplier disini . . .">
                        Alamat
                    </x-textarea>
                    <x-inline-error-message x-show="addressError" x-text="addressError"></x-inline-error-message>
                    @error('address')
                        <x-inline-error-message x-show="addressServerError">{{ $message }}</x-inline-error-message>
                    @enderror
                </div>

                <div class="flex justify-center gap-6">
                    <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                        <a href="{{ route('supplier.index') }}">Batal</a>
                    </x-button-sm>
                    <x-button-sm type="submit"
                        class="w-fit px-7 text-secondary-purple bg-secondary-purple/20">Buat</x-button-sm>
                </div>
            </div>
        </div>
    </form>
</x-layout>
