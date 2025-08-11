<x-guest-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <!-- style="background: linear-gradient(135deg, #6366f1 0%, #a5b4fc 100%);" -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4"
                        style="background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">
                        <div class="row g-0">
                            <!-- Left Side: Logo/Image -->
                            <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center p-5"
                                style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%); border-top-left-radius:1.5rem; border-bottom-left-radius:1.5rem;">
                                <div class="text-center w-100">
                                    <img src="{{ asset('assets/Image/logo.jpg') }}" alt="Logo"
                                        class="rounded-circle shadow mb-4"
                                        style="width: 150px; height: 150px; object-fit: contain; background: #fff;" />
                                    <h2 class="text-white fw-bold mb-2">Join Us!</h2>
                                    <p class="text-white-50 mb-0">Create your account to get started.</p>
                                    <p class="text-white mt-4 fs-5">Welcome to <br> Cagayan Valley Health Research and
                                        Development Consortium</p>
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
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autofocus autocomplete="name"
                                                placeholder="Enter your name">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-primary">{{ __('Email') }}</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="username"
                                                placeholder="Enter your email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password"
                                                class="form-label text-primary">{{ __('Password') }}</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password"
                                                placeholder="Enter your password">
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirmation"
                                                class="form-label text-primary">{{ __('Confirm Password') }}</label>
                                            <input id="password_confirmation" type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Confirm your password">
                                            @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Terms and Conditions -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input id="terms" type="checkbox" class="form-check-input" name="terms"
                                                    disabled>
                                                <label class="form-check-label" for="terms">
                                                    {!! __('I agree to the <a href="#" class="text-primary"
                                                        id="open-terms-modal">Terms and Conditions</a>') !!}
                                                </label>
                                            </div>
                                            <div id="terms-alert" class="d-none mt-2 text-danger small">
                                                {{ __('You must agree to the Terms and Conditions before registering.') }}
                                            </div>
                                        </div>

                                        <!-- reCAPTCHA widget -->
                                        <div class="mb-3">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                            @error('g-recaptcha-response')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <a class="text-decoration-none text-primary" href="{{ route('login') }}">
                                                {{ __('Already registered?') }}
                                            </a>
                                            <button id="register-btn" type="submit"
                                                class="btn btn-primary px-4 shadow-sm" disabled>
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
        });

    </script>
</x-guest-layout>
