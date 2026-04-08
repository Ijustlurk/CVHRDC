<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold text-secondary small uppercase tracking-wider">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                <input id="name" name="name" type="text" class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
            </div>
            @error('name')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-secondary small uppercase tracking-wider">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                <input id="email" name="email" type="email" class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-2 bg-warning-subtle rounded border border-warning-subtle">
                    <p class="small text-warning-emphasis mb-1">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success small fw-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="address" class="form-label fw-semibold text-secondary small uppercase tracking-wider">Complete Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-map-marker-alt"></i></span>
                <input id="address" name="address" type="text" class="form-control bg-light border-start-0 ps-0 @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}" required placeholder="Lot No, Street, City, Province, Zip Code">
            </div>
            <div class="form-text x-small text-muted mt-1">Include Lot No, Building, Street, City/Town, Province, and Postal Code.</div>
            @error('address')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                <i class="fas fa-save me-2"></i>Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-success small fw-medium">
                    <i class="fas fa-check-circle me-1"></i>Saved successfully!
                </span>
            @endif
        </div>
    </form>
</section>
