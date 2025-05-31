<x-layout>
    <x-slot:title>Buat Kasir</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('cashier.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Kasir</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Akun Kasir
        </div>
    </x-slot:header>

    <!-- Toast Error -->
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

    <form action="{{ route('cashier.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-10 grid grid-cols-2 gap-8"
            x-data="{
                name: @js(old('name')),
                email: @js(old('email')),
                phone: @js(old('phone_number')),
                password: @js(old('password')),
                nameError: '',
                emailError: '',
                phoneError: '',
                passwordError: '',
                nameServerError: '{{ $errors->has('name') }}',
                emailServerError: '{{ $errors->has('email') }}',
                phoneServerError: '{{ $errors->has('phone_number') }}',
                passwordServerError: '{{ $errors->has('password') }}',
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
                validateEmail() {
                    this.emailError = '';

                    if (this.email == false) {
                        this.emailError = 'Email wajib diisi';
                    } else if (!/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
                        this.emailError = 'Format email tidak valid';
                    }
                    if (this.email !== '') {
                        this.emailServerError = false;
                    }
                },
                validatePhone() {
                    this.phoneError = '';

                    if (this.phone == false) {
                        this.phoneError = 'Nomor handphone wajib diisi';
                    } else if (!/^08/.test(this.phone)) {
                        this.phoneError = 'Nomor harus berformat 08xxxxxxxxxxx';
                    } else if (!/^[0-9]+$/.test(this.phone)) {
                        this.phoneError = 'Nomor hanya boleh berisi angka';
                    } else if(!/^[0-9]{10,15}$/.test(this.phone)) {
                        this.phoneError = 'Nomor harus berjumlah 10-15 digit';
                    }
                    if(this.phone !== '') {
                        this.phoneServerError = false;
                    }
                },
                validatePassword() {
                    this.passwordError = '';

                    if (this.password == false) {
                        this.passwordError = 'Password wajib diisi';
                    }
                    if (this.password !== '') {
                        this.passwordServerError = false;
                    }
                }
            }"
        >
            <div class="col-span-1">
                {{-- Nama --}}
                <x-textfield x-model="name" x-on:input="validateName()" classCont="mb-4" class="focus:ring" x-bind:class="nameError || nameServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring-primary'" type="text" name="name"
                    placeholder="Masukkan nama lengkap disini . . .">Nama</x-textfield>
                <x-inline-error-message class="mb-4 -mt-1" x-show="nameError" x-text="nameError"></x-inline-error-message>
                @error('name')
                    <x-inline-error-message class="mb-4 -mt-1" x-show="nameServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- Email --}}
                <x-textfield x-model="email" x-on:input="validateEmail()" classCont="mb-4" class="focus:ring" x-bind:class="emailError || emailServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring focus:ring-primary'" type="email" name="email"
                    placeholder="Masukkan email disini . . .">Email</x-textfield>
                <x-inline-error-message class="mb-4 -mt-1" x-show="emailError" x-text="emailError"></x-inline-error-message>
                @error('email')
                    <x-inline-error-message class="mb-4 -mt-1" x-show="emailServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- Password --}}
                <x-textfield-password x-model="password" x-on:input="validatePassword()" classCont="mb-4" class="focus:ring" x-bind:class="passwordError || passwordServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring focus:ring-primary'" name="password"
                    placeholder="Masukkan password disini . . .">Password</x-textfield-password>
                <x-inline-error-message class="mb-4 -mt-1" x-show="passwordError" x-text="passwordError"></x-inline-error-message>
                @error('password')
                    <x-inline-error-message class="mb-4 -mt-1" x-show="passwordServerError">{{ $message }}</x-inline-error-message>
                @enderror

                {{-- No Handphone --}}
                <x-textfield x-model="phone" x-on:input="validatePhone()" classCont="mb-4" class="focus:ring" x-bind:class="phoneError || phoneServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring focus:ring-primary'" type="text" name="phone_number"
                    placeholder="08xxxxxxxxxx">No Handphone</x-textfield>
                <x-inline-error-message class="mb-4 -mt-1" x-show="phoneError" x-text="phoneError"></x-inline-error-message>
                @error('phone_number')
                    <x-inline-error-message class="mb-4 -mt-1" x-show="phoneServerError">{{ $message }}</x-inline-error-message>
                @enderror
                <input type="hidden" name="role" value="cashier">
            </div>
            <div class="col-span-1">
                <x-textfield-image class="h-3/4" name="photo">Foto<span class="ml-2 text-xs text-tertiary-400 font-semibold">(format .jpg/.jpeg, max. 2 mb)</span></x-textfield-image>
                <div class="col-span-2 flex justify-center gap-6 mt-10">
                    <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                        <a href="{{ route('cashier.index') }}">Batal</a>
                    </x-button-sm>
                    <x-button-sm type="submit"
                        class="w-fit px-7 text-secondary-purple bg-secondary-purple/20">Buat</x-button-sm>
                </div>
            </div>
        </div>
    </form>
</x-layout>
