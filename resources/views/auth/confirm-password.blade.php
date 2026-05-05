<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-lock" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Confirm Password</h1>
        <p class="text-secondary mb-0">This is a secure area. Confirm your password to continue.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
