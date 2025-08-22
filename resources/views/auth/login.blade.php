<x-guest-layout>

    <!-- Decorative Shapes (fixed background) -->
    <div class="position-fixed top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
        <!-- Top Left -->
        <div style="position: absolute; top: 20px; left: 20px; width: 180px; height: 180px;
                    background: rgba(99, 102, 241, 0.25); border-radius: 50%;"></div>

        <!-- Bottom Right -->
        <div style="position: absolute; bottom: 20px; right: 20px; width: 120px; height: 120px;
                    background: rgba(16, 185, 129, 0.25); border-radius: 50%;"></div>

        <!-- Middle Left -->
        <div style="position: absolute; top: 50%; left: 20px; width: 80px; height: 80px;
                    background: rgba(255, 193, 7, 0.3); border-radius: 50%; transform: translateY(-50%);"></div>

        <!-- Middle Bottom -->
        <div style="position: absolute; bottom: 120px; left: 50%; width: 60px; height: 60px;
                    background: rgba(220, 38, 38, 0.3); border-radius: 50%; transform: translateX(-50%);"></div>

        <!-- Spread Shapes -->
        <div style="position: absolute; top: 10%; left: 50%; width: 70px; height: 70px;
                    background: rgba(59, 130, 246, 0.3); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: 10%; right: 25%; width: 50px; height: 50px;
                    background: rgba(236, 72, 153, 0.3); border-radius: 50%;"></div>
        <div style="position: absolute; top: 75%; left: 25%; width: 90px; height: 90px;
                    background: rgba(132, 204, 22, 0.3); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: 50%; right: 50%; width: 40px; height: 40px;
                    background: rgba(20, 184, 166, 0.3); border-radius: 50%;"></div>
        <div style="position: absolute; top: 40%; left: 75%; width: 60px; height: 60px;
                    background: rgba(168, 85, 247, 0.3); border-radius: 50%;"></div>
    </div>

    <!-- Login Card -->
    <div class="d-flex align-items-center justify-content-center min-vh-100" style="position: relative; z-index: 1;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4"
                        style="background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">
                        <div class="row g-0">
                            <!-- Left Side: Logo/Image -->
                            <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center p-5"
                                style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); border-top-left-radius:1.5rem; border-bottom-left-radius:1.5rem;">
                                <div class="text-center">
                                    <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo"
                                        class="rounded-circle mb-3"
                                        style="width: 150px; height: 150px; object-fit: contain; background: #fff; box-shadow: 0 4px 24px 0 rgba(99,102,241,0.15);" />
                                    <h2 class="text-white fw-bold mb-2">Welcome Back!</h2>
                                    <p class="text-white-50 mb-0">Sign in to continue to your account.</p>
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

                                    <h2 class="text-center text-primary fw-bold mb-4">Login to Your Account</h2>

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email') }}</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autofocus autocomplete="username"
                                                placeholder="Enter your email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('Password') }}</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password"
                                                placeholder="Enter your password">
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="remember">{{ __('Remember Me') }}</label>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            @if (Route::has('password.request'))
                                            <a class="text-decoration-none text-primary"
                                                href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                            @endif

                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <span class="d-flex align-items-center">
                                                    <span>{{ __('Log in') }}</span>
                                                    <span id="loading-spinner"
                                                        class="spinner-border spinner-border-sm ms-2 d-none"
                                                        role="status"></span>
                                                </span>
                                            </button>
                                        </div>

                                        @if (Route::has('register'))
                                        <div class="text-center mt-3">
                                            <span class="text-muted">{{ __("Don't have an account?") }}</span>
                                            <a href="{{ route('register') }}"
                                                class="text-primary text-decoration-none ms-1 fw-semibold">
                                                {{ __('Register') }}
                                            </a>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <!-- End Right Side -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spinner disable script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginBtn = document.querySelector('button[type="submit"]');
            const loadingSpinner = document.getElementById('loading-spinner');
            const loginForm = loginBtn.closest('form');
            loginForm.addEventListener('submit', function () {
                loginBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
            });
        });

    </script>

</x-guest-layout>
