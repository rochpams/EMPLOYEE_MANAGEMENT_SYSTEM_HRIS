@extends('layouts.app')

@section('title', isset($title) ? $title : 'Dashboard')
@section('subtitle', isset($subtitle) ? $subtitle : 'Manage employees, departments, attendance, and leave requests.')

@section('content')
    {{ $slot }}
<script>
    (function(){
        if (window.Chart) {
            try {
                const root = document.documentElement;
                const muted = getComputedStyle(root).getPropertyValue('--app-muted') || '#5b6776';
                Chart.defaults.font.family = "'Manrope', 'Segoe UI', sans-serif";
                Chart.defaults.color = muted.trim();
                if (Chart.defaults.plugins && Chart.defaults.plugins.legend) {
                    Chart.defaults.plugins.legend.labels = Chart.defaults.plugins.legend.labels || {};
                    Chart.defaults.plugins.legend.labels.color = muted.trim();
                }
            } catch (e) {
                // noop
            }
        }
    })();
</script>
@endsection
