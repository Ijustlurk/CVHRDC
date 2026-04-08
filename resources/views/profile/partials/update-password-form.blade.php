<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold text-secondary small uppercase tracking-wider">Current Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock-open"></i></span>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control bg-light border-start-0 ps-0 @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
            </div>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold text-secondary small uppercase tracking-wider">New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-key"></i></span>
                <input id="update_password_password" name="password" type="password" class="form-control bg-light border-start-0 ps-0 @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
            </div>
            @error('password', 'updatePassword')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label fw-semibold text-secondary small uppercase tracking-wider">Confirm New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-check-double"></i></span>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control bg-light border-start-0 ps-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
            </div>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                <i class="fas fa-shield-alt me-2"></i>Update Password
            </button>

            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-success small fw-medium">
                    <i class="fas fa-check-circle me-1"></i>Password updated!
                </span>
            @endif
        </div>
    </form>
</section>
