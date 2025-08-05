<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="row g-0">
            <!-- Left Side: Logo/Image -->
                        <div class="col-md-5 bg-primary bg-gradient d-none d-md-flex align-items-center justify-content-center p-5">
                            <div class="text-center">
                                <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" class="rounded-circle shadow mb-4" style="width: 150px; height: 150px; object-fit: contain;" />
                                <h2 class="text-white fw-bold">Welcome Back!</h2>
                                <p class="text-white-50">Sign in to continue to your account.</p>
                            </div>
            </div>
                        
            <!-- Right Side: Login Form -->
                        <div class="col-md-7">
                            <div class="card-body p-4">
                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="alert alert-info mb-4">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <h1 class="text-center text-primary fw-bold mb-4">Login to Your Account</h1>
                                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                    </div>

                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required autocomplete="current-password">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                    </div>

<!-- Terms and Conditions -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input id="terms" type="checkbox" class="form-check-input" name="terms">
                                            <label class="form-check-label" for="terms">
                                                {!! __('I agree to the <a href="/terms" class="text-primary" target="_blank">Terms and Conditions</a>') !!}
</label>
                                        </div>
</div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
    @if (Route::has('password.request'))
                                            <a class="text-decoration-none text-primary" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    @endif

                                                                                <button type="submit" id="login-btn" class="btn btn-primary px-4" disabled>
                                            <span class="d-flex align-items-center">
                                                <span>{{ __('Log in') }}</span>
                                                <span id="loading-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                            </span>
                                        </button>
</div>

    @if (Route::has('register'))
                                        <div class="text-center">
                                            <span class="text-muted">{{ __("Don't have an account?") }}</span>
                                            <a href="{{ route('register') }}" class="text-primary text-decoration-none ms-1">
            {{ __('Register') }}
        </a>
                                        </div>
    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const termsCheckbox = document.getElementById('terms');
            const loginBtn = document.getElementById('login-btn');
            const loginForm = loginBtn.closest('form');
            const loadingSpinner = document.getElementById('loading-spinner');

            function toggleLoginBtn() {
                loginBtn.disabled = !termsCheckbox.checked;
            }

            loginForm.addEventListener('submit', function(e) {
                if (loginBtn.disabled) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                loginBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
            });

            termsCheckbox.addEventListener('change', toggleLoginBtn);
            toggleLoginBtn();
        });
    </script>
    @endpush
</x-guest-layout>