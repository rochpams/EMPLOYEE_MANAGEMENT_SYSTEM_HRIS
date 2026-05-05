@extends('layouts.app')

@section('title', 'Departments')
@section('subtitle', 'Manage organizational departments and managers.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-secondary small">{{ $departments->count() }} department{{ $departments->count() === 1 ? '' : 's' }}</div>
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
            Add Department
        </a>
    @endif
</div>

<div class="row g-3">
    @forelse($departments as $dept)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-soft h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 mb-0">{{ $dept->name }}</h2>
                        <span class="status-badge status-{{ $dept->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($dept->status) }}</span>
                    </div>

                    <p class="text-secondary mb-3">{{ $dept->description ?? 'No description provided.' }}</p>

                    <div class="small mb-2"><strong>Manager:</strong> {{ $dept->manager->name ?? 'Not assigned' }}</div>
                    <div class="small text-secondary mb-3">{{ $dept->employees->count() }} employee{{ $dept->employees->count() === 1 ? '' : 's' }}</div>

                    <div class="mt-auto d-flex gap-2 flex-wrap">
                        <a href="{{ route('departments.show', $dept) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-eye me-1" aria-hidden="true"></i>
                            View
                        </a>

                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
                            <a href="{{ route('departments.edit', $dept) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1" aria-hidden="true"></i>
                                Edit
                            </a>

                            <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1" aria-hidden="true"></i>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card card-soft">
                <div class="card-body text-center py-5 text-secondary">
                    <i class="bi bi-diagram-3 d-block fs-2 mb-2" aria-hidden="true"></i>
                    No departments found.
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
