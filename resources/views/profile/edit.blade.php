<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Account Settings</p>
                    <h1 class="hris-title">Profile</h1>
                    <p class="hris-subtitle">Manage your account information, password, and account deletion settings.</p>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
</x-app-layout>
