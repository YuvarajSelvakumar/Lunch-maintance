@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px;">
    <h2>Edit Weekly Menu for {{ $menu->day }}, Month: {{ $menu->month }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('weekly-menu.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="meal_type">Meal Type</label>
            <select name="meal_type" id="meal_type" class="form-control" required>
                <option value="veg" {{ $menu->meal_type === 'veg' ? 'selected' : '' }}>Veg</option>
                <option value="egg" {{ $menu->meal_type === 'egg' ? 'selected' : '' }}>Egg</option>
                <option value="chicken" {{ $menu->meal_type === 'chicken' ? 'selected' : '' }}>Chicken</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Menu</button>
        <a href="{{ route('weekly-menu.index', ['month' => $menu->month]) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
