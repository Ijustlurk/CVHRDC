<x-guest-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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


    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg border-0 rounded-4"
            style="max-width: 1000px; width: 100%; background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">

            <div class="row g-0">
                <!-- Left Side: Logo/Image -->
                <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
                        border-top-left-radius:1.5rem; border-bottom-left-radius:1.5rem;">
                    <div class="text-center w-100">
                        <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo" class="rounded-circle shadow mb-4"
                            style="width: 150px; height: 150px; object-fit: contain; background: #fff;" />
                        <h2 class="text-white fw-bold mb-2">Join Us!</h2>
                        <p class="text-white-50 mb-0">Create your account to get started.</p>
                        <p class="text-white mt-4 fs-5">Welcome to <br>
                            Cagayan Valley Health Research and Development Consortium
                        </p>
                    </div>
                </div>

                <!-- Right Side: Register Form -->
                <div class="col-md-7">
                    <div class="card-body p-4">
                        <h2 class="text-center text-primary fw-bold mb-4">Register Your Account</h2>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label text-primary">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                    placeholder="Enter your name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-primary">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="username"
                                    placeholder="Enter your email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-primary">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password" placeholder="Enter your password">
                                    <button type="button" class="btn btn-secondary toggle-password"
                                        data-target="password" aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation"
                                    class="form-label text-primary">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <input id="password_confirmation" type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm your password">
                                    <button type="button" class="btn btn-secondary toggle-password"
                                        data-target="password_confirmation" aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input id="terms" type="checkbox" class="form-check-input" name="terms" disabled>
                                    <label class="form-check-label" for="terms">
                                        {!! __('I agree to the <a href="#" class="text-primary" id="open-terms-modal">
                                            Terms and Conditions</a>') !!}
                                    </label>
                                </div>
                                <div id="terms-alert" class="d-none mt-2 text-danger small">
                                    {{ __('You must agree to the Terms and Conditions before registering.') }}
                                </div>
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="mb-3">
                                {{-- {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!} --}}
                                @error('g-recaptcha-response')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a class="text-decoration-none text-primary" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>
                                <button id="register-btn" type="submit" class="btn btn-primary px-4 shadow-sm" disabled>
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <p>
                            <!-- Replace this with your actual terms and conditions -->
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam euismod, urna eu tincidunt
                            consectetur, nisi nisl aliquam nunc, eget aliquam massa nisi eu velit. Suspendisse potenti.
                        </p>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="" id="modal-terms-checkbox">
                        <label class="form-check-label" for="modal-terms-checkbox">
                            I have read and agree to the Terms and Conditions
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="accept-terms-btn" class="btn btn-primary" disabled>Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const termsCheckbox = document.getElementById('terms');
            const registerBtn = document.getElementById('register-btn');
            const termsAlert = document.getElementById('terms-alert');
            const form = registerBtn.closest('form');
            const openTermsModal = document.getElementById('open-terms-modal');
            const modalTermsCheckbox = document.getElementById('modal-terms-checkbox');
            const acceptTermsBtn = document.getElementById('accept-terms-btn');
            const termsModal = new bootstrap.Modal(document.getElementById('termsModal'));

            // Open modal on link click
            openTermsModal.addEventListener('click', function (e) {
                e.preventDefault();
                modalTermsCheckbox.checked = false;
                acceptTermsBtn.disabled = true;
                termsModal.show();
            });

            // Enable Accept button only if modal checkbox is checked
            modalTermsCheckbox.addEventListener('change', function () {
                acceptTermsBtn.disabled = !modalTermsCheckbox.checked;
            });

            // When Accept is clicked, check the main checkbox and enable register
            acceptTermsBtn.addEventListener('click', function () {
                termsCheckbox.checked = true;
                termsCheckbox.disabled = false;
                registerBtn.disabled = false;
                termsAlert.classList.add('d-none');
                termsModal.hide();
            });

            // Prevent form submission if terms not checked
            form.addEventListener('submit', function (e) {
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    termsAlert.classList.remove('d-none');
                }
            });

            // Toggle password visibility buttons (works for both password fields)
            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                        this.setAttribute('aria-label', 'Hide password');
                    } else {
                        input.type = 'password';
                        this.innerHTML = '<i class="fas fa-eye"></i>';
                        this.setAttribute('aria-label', 'Show password');
                    }
                });
            });
        });

    </script>
</x-guest-layout>
