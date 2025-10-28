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

    <!-- Forgot Password Card -->
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
                                    <h2 class="text-white fw-bold mb-2">Forgot Password?</h2>
                                    <p class="text-white-50 mb-0">Reset your password to regain access.</p>
                                </div>
                            </div>

                            <!-- Right Side: Forgot Password Form -->
                            <div class="col-md-7">
                                <div class="card-body p-4">
                                    <!-- Session Status -->
                                    @if (session('status'))
                                    <div class="alert alert-info mb-4">
                                        {{ session('status') }}
                                    </div>
                                    @endif

                                    <h2 class="text-center text-primary fw-bold mb-4">Reset Your Password</h2>

                                    <p class="text-muted mb-4 text-center">
                                        Enter your registered email address below, and we will send you a link to reset your password. Please check your inbox (and spam folder) for the reset email. The link will be valid for a limited time.
                                    </p>

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email') }}</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autofocus
                                                placeholder="Enter your email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end align-items-center mb-3">
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                <span class="d-flex align-items-center">
                                                    <span>{{ __('Email Password Reset Link') }}</span>
                                                    <span id="loading-spinner"
                                                        class="spinner-border spinner-border-sm ms-2 d-none"
                                                        role="status"></span>
                                                </span>
                                            </button>
                                        </div>
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
            const submitBtn = document.querySelector('button[type="submit"]');
            const loadingSpinner = document.getElementById('loading-spinner');
            const form = submitBtn.closest('form');
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
            });
        });

    </script>

</x-guest-layout>
