@props([
    'variant' => 'info',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'alert alert-' . $variant . ' d-flex align-items-start gap-2']) }} role="alert">
    @if($icon)
        <i class="bi {{ $icon }}" aria-hidden="true"></i>
    @endif
    <div>{{ $slot }}</div>
</div>
