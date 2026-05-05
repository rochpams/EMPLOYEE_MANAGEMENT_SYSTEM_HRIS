@extends('layouts.app')

@section('title', isset($title) ? $title : 'Dashboard')
@section('subtitle', isset($subtitle) ? $subtitle : 'Manage employees, departments, attendance, and leave requests.')

@section('content')
    {{ $slot }}
@endsection
