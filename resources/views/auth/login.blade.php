<x-guest-layout>
    {{-- <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-indigo-400 py-12 px-4"> --}}
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg flex overflow-hidden">
            <!-- Left Side: Logo/Image -->
            <div class="hidden md:flex flex-col justify-center items-center bg-indigo-100 w-1/2 p-8">
                <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" class="h-32 w-32 rounded-full shadow-md bg-gray-100 object-contain mb-4" />
                <h2 class="text-2xl font-bold text-indigo-700 mt-4">Welcome Back!</h2>
                <p class="text-gray-500 mt-2 text-center">Sign in to continue to your account.</p>
            </div>
            <!-- Right Side: Login Form -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <h1 class="text-2xl font-bold text-center text-indigo-700 mb-6">Login to Your Account</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-indigo-700" />
                        <x-text-input id="email" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" class="text-indigo-700" />
                        <x-text-input id="password" class="block mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

<!-- Terms and Conditions -->
<div class="flex items-center mt-2">
    <input id="terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="terms">
    <label for="terms" class="ms-2 text-sm text-gray-600">
    {!! __('I agree to the <a href="/terms" class="text-indigo-600 hover:text-indigo-900" target="_blank">Terms and Conditions</a>') !!}
</label>
</div>

<div class="flex items-center justify-between mt-6">
    @if (Route::has('password.request'))
        <a class="text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    @endif

    <x-primary-button id="login-btn" class="ms-3 bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 transition" disabled>
        {{ __('Log in') }}
    </x-primary-button>
</div>
<div class="mt-4 text-center">
    @if (Route::has('register'))
        <span class="text-sm text-gray-600">{{ __("Don't have an account?") }}</span>
        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900 underline ml-1">
            {{ __('Register') }}
        </a>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const termsCheckbox = document.getElementById('terms');
        const loginBtn = document.getElementById('login-btn');

        function toggleLoginBtn() {
            loginBtn.disabled = !termsCheckbox.checked;
        }

        termsCheckbox.addEventListener('change', toggleLoginBtn);
        toggleLoginBtn();
    });
</script>
@endpush
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>