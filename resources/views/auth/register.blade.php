<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-person-plus" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Create Account</h1>
        <p class="text-secondary mb-0">Register a new HRIS user account.</p>
    </div>

    @if ($errors->any())
        <x-alert variant="danger" icon="bi-exclamation-triangle-fill" class="mb-3">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('register') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-person-check me-1" aria-hidden="true"></i>
            Register
        </button>
    </form>

    <p class="text-center text-secondary mt-4 mb-0">
        Already registered? <a href="{{ route('login') }}" class="fw-semibold">Sign In</a>
    </p>
</x-guest-layout>
