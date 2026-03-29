@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Add Employee</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
        </div>

        <div class="mb-3">
            <label>Hire Date</label>
            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date') }}" required>
        </div>

        <div class="mb-3">
            <label>Employment Status</label>
            <select name="employment_status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="terminated">Terminated</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Employee</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection