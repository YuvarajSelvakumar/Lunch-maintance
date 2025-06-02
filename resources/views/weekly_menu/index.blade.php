@extends('layouts.app')

@section('content')
<h2>Weekly Menu</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('weekly-menu.store') }}" class="mb-4">
    @csrf
    <div class="row g-3">
        <div class="col-md-3">
            <label>Month</label>
            <input type="month" name="month" class="form-control" required value="{{ old('month') }}">
            @error('month')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label>Day of Week</label>
            <select name="day_of_week" class="form-select" required>
                <option value="">Select Day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
            @error('day_of_week')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label>Meal Type</label>
            <select name="meal_type" class="form-select" required>
                <option value="">Select Meal</option>
                @foreach(['Veg','Egg','Chicken'] as $meal)
                <option value="{{ $meal }}" {{ old('meal_type') == $meal ? 'selected' : '' }}>{{ $meal }}</option>
                @endforeach
            </select>
            @error('meal_type')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="col-md-3">
            <label>Effective From</label>
            <input type="date" name="effective_from" class="form-control" required value="{{ old('effective_from') }}">
            @error('effective_from')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Weekly Menu</button>
</form>

<h3>Existing Weekly Menus</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Month</th>
            <th>Day</th>
            <th>Meal Type</th>
            <th>Version</th>
            <th>Effective From</th>
        </tr>
    </thead>
    <tbody>
        @forelse($weeklyMenus as $menu)
        <tr>
            <td>{{ \Carbon\Carbon::parse($menu->month)->format('F Y') }}</td>
            <td>{{ $menu->day_of_week }}</td>
            <td>{{ $menu->meal_type }}</td>
            <td>{{ $menu->version }}</td>
            <td>{{ $menu->effective_from }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No weekly menus found</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
