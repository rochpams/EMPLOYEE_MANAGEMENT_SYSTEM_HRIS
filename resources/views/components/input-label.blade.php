@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold small text-uppercase text-muted']) }}>
    {{ $value ?? $slot }}
</label>
