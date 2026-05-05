<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-envelope-check" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Verify Your Email</h1>
        <p class="text-secondary mb-0">Please confirm your email before continuing.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2 mb-3" role="alert">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="alert alert-info" role="alert">
        {{ __('Thanks for signing up! Before getting started, verify your email by clicking the link we sent you. If you did not receive it, request another below.') }}
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary btn-sm">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
