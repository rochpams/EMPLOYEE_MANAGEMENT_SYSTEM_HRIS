<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-key" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Forgot Password</h1>
        <p class="text-secondary mb-0">Enter your email and we will send a password reset link.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope-arrow-up me-1" aria-hidden="true"></i>
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
