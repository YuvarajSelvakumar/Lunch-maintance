@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <h2>Edit Daily Lunch Entry</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

    <form method="POST" action="{{ route('daily-lunch.update', $entry->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="entry_date" class="form-control" value="{{ $entry->entry_date }}" required>
            @error('entry_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="day_name" class="form-label">Day</label>
            <input type="text" id="day_name" name="day_name" class="form-control" value="{{ $entry->day_name }}" readonly>
            @error('day_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="meal_type" class="form-label">Meal Type</label>
            <input type="text" id="meal_type" name="meal_type" class="form-control" value="{{ $entry->meal_type }}" readonly>
            @error('meal_type') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="meal_price" class="form-label">Meal Price</label>
            <input type="text" id="meal_price" name="meal_price" class="form-control" value="{{ $entry->meal_price }}" readonly>
            @error('meal_price') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="count" class="form-label">Count</label>
            <input type="number" id="count" name="count" class="form-control" value="{{ $entry->count }}" min="1" required>
            @error('count') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="total_cost" class="form-label">Total Cost</label>
            <input type="text" id="total_cost" name="total_cost" class="form-control" value="{{ $entry->total_cost }}" readonly>
            @error('total_cost') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Entry</button>
        <a href="{{ route('daily-lunch.create') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    const dateInput = document.getElementById('date');
    const countInput = document.getElementById('count');
    const mealPriceInput = document.getElementById('meal_price');
    const totalCostInput = document.getElementById('total_cost');

    dateInput.addEventListener('change', async function () {
        const selectedDate = this.value;
        if (!selectedDate) return;

        try {
            const response = await fetch(`{{ route('daily-lunch.get-meal-info') }}?date=${selectedDate}`);
            const data = await response.json();

            document.getElementById('day_name').value = data.day_name ?? '';
            document.getElementById('meal_type').value = data.meal_type ?? '';
            mealPriceInput.value = data.price ?? '';

            const count = parseInt(countInput.value || 0);
            totalCostInput.value = (data.price ?? 0) * count;
        } catch (error) {
            console.error('Error fetching meal info:', error);
        }
    });

    countInput.addEventListener('input', function () {
        const price = parseFloat(mealPriceInput.value || 0);
        const count = parseInt(countInput.value || 0);
        totalCostInput.value = price * count;
    });
</script>
@endsection
