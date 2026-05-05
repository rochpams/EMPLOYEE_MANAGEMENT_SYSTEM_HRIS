<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-shield-lock" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Reset Password</h1>
        <p class="text-secondary mb-0">Set a new password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="d-grid gap-3">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
