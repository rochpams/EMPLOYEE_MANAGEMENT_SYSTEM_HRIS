@props([
    'variant' => 'primary',
    'type' => 'button',
    'icon' => null,
])

@php
    $variantClass = match ($variant) {
        'secondary' => 'btn-outline-secondary',
        'danger' => 'btn-danger',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        default => 'btn-primary',
    };
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => 'btn ' . $variantClass]) }}>
    @if($icon)
        <i class="bi {{ $icon }} me-1" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</button>
