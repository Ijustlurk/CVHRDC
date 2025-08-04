<x-guest-layout>
    {{-- <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-400 via-white to-indigo-00 py-12 px-4"> --}}
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg flex overflow-hidden">
            <!-- Left Side: Logo/Image -->
            <div class="hidden md:flex flex-col justify-center items-center bg-indigo-100 w-1/2 p-8">
                <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" class="h-32 w-32 rounded-full shadow-md bg-gray-100 object-contain mb-4" />
                <h2 class="text-2xl font-bold text-indigo-700 mt-4">Join Us!</h2>
                <p class="text-gray-500 mt-2 text-center">Create your account to get started.</p>
            </div>
            <!-- Right Side: Register Form -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h1 class="text-2xl font-bold text-center text-indigo-700 mb-6">Register Your Account</h1>
                <form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" class="text-indigo-700" />
        <x-text-input id="name" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" class="text-indigo-700" />
        <x-text-input id="email" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" class="text-indigo-700" />
        <x-text-input id="password" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            type="password"
            name="password"
            required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-indigo-700" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            type="password"
            name="password_confirmation" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <!-- Terms and Conditions -->
    <div class="flex items-center mt-2">
        <input id="terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="terms">
        <label for="terms" class="ms-2 text-sm text-gray-600">
    {!! __('I agree to the <a href="/terms" class="text-indigo-600 hover:text-indigo-900" target="_blank">Terms and Conditions</a>') !!}
</label>
    </div>
    <div id="terms-alert" class="hidden mt-2 text-sm text-red-600">
        {{ __('You must agree to the Terms and Conditions before registering.') }}
    </div>

    <div class="flex items-center justify-between mt-6">
    <a class="text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition" href="{{ route('login') }}">
        {{ __('Already registered?') }}
    </a>
    <button id="register-btn" type="submit" class="ms-3 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 transition px-4 py-2 rounded text-white" disabled>
        {{ __('Register') }}
    </button>
</div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const termsCheckbox = document.getElementById('terms');
    const registerBtn = document.getElementById('register-btn');
    const termsAlert = document.getElementById('terms-alert');
    const form = registerBtn.closest('form');

    function toggleRegisterBtn() {
        registerBtn.disabled = !termsCheckbox.checked;
        if (termsCheckbox.checked) {
            termsAlert.classList.add('hidden');
        }
    }

    termsCheckbox.addEventListener('change', toggleRegisterBtn);
    toggleRegisterBtn();

    form.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            termsAlert.classList.remove('hidden');
        }
    });
});
</script>
@endpush
            </div>
        </div>
    </div>
</x-guest-layout>