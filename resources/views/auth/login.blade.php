<x-guest-layout>
    <div class="text-center mb-4">
        <div class="auth-icon mx-auto mb-3">
            <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
        </div>
        <h1 class="h4 fw-bold mb-1">Welcome</h1>
        <p class="text-secondary mb-0">Access your HRIS dashboard.</p>
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

    <form method="POST" action="{{ route('login') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@company.com" required autofocus>
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2" >
            <div class="form-check d-flex align-items-center justify-content-start p-0 gap-2" >
                <input class="" type="checkbox" id="remember" name="remember" >
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
            Sign In
        </button>
    </form>

    <!-- <p class="text-center text-secondary mt-4 mb-0">
        Need an account? <a href="{{ route('register') }}" class="fw-semibold">Register</a>
    </p> -->
</x-guest-layout>
