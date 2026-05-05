@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link px-2 border-bottom border-2 border-primary text-primary fw-semibold'
            : 'nav-link px-2 text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
