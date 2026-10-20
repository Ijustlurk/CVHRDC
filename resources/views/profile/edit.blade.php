@extends('layouts.app-profile')

@section('content')
<div class="row g-4">
    <!-- Sidebar Navigation -->
    <div class="col-lg-3">
        <div class="card profile-nav-card p-3 sticky-top" style="top: 2rem; z-index: 10;">
            <div class="text-center mb-4 pt-3">
                <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 class="mb-1 text-capitalize">{{ Auth::user()->name }}</h5>
                <p class="text-muted small">{{ Auth::user()->email }}</p>
                <div class="badge bg-light text-primary border border-primary-subtle px-3 py-2">
                    {{ Auth::user()->roles->isNotEmpty() ? ucfirst(Auth::user()->roles[0]->name) : 'User' }}
                </div>
            </div>

            <nav class="nav flex-column mb-3">
                <a class="profile-nav-link active" href="#profile-info">
                    <i class="fas fa-user-circle"></i> Profile Details
                </a>
                <a class="profile-nav-link" href="#security">
                    <i class="fas fa-shield-alt"></i> Account Security
                </a>
            </nav>
            
            <hr class="text-muted opacity-25">
            
            <div class="px-3 pt-2">
                <p class="text-muted x-small mb-2">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-9">
        <div class="space-y-4">
            <!-- Profile Information Section -->
            <div id="profile-info" class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Public Profile</h5>
                    <p class="text-muted small mb-0">Manage your basic information and address</p>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Password Update Section -->
            <div id="security" class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Security Settings</h5>
                    <p class="text-muted small mb-0">Update your account password to ensure security</p>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Optional: Delete Account Section
            <div id="danger-zone" class="card border-0 shadow-sm rounded-4 overflow-hidden border-start border-danger border-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-danger mb-0">Danger Zone</h5>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>
</div>

@push('scripts')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.75rem; }
    .max-w-xl { max-width: 36rem; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.profile-nav-link');
        
        // Simple scroll spy logic
        window.addEventListener('scroll', function() {
            let fromTop = window.scrollY + 100;
            
            navLinks.forEach(link => {
                let section = document.querySelector(link.hash);
                
                if (
                    section.offsetTop <= fromTop &&
                    section.offsetTop + section.offsetHeight > fromTop
                ) {
                    navLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            });
        });
        
        // Smooth scroll
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.hash).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endpush
@endsection


