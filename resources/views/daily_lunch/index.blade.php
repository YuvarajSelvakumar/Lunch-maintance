@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <h2>Add Daily Lunch Entry</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @if(session('error'))
    @if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif

@endif

    @endif

    <form method="POST" action="{{ route('daily-lunch.store') }}">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="entry_date" class="form-control" required>
            @error('entry_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="day_name" class="form-label">Day</label>
            <input type="text" id="day_name" name="day_name" class="form-control" readonly>
            @error('day_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="meal_type" class="form-label">Meal Type</label>
            <input type="text" id="meal_type" name="meal_type" class="form-control" readonly>
            @error('meal_type') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="meal_price" class="form-label">Meal Price</label>
            <input type="text" id="meal_price" name="meal_price" class="form-control" readonly>
            @error('meal_price') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="count" class="form-label">Count</label>
            <input type="number" id="count" name="count" class="form-control" min="1" required>
            @error('count') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="total_cost" class="form-label">Total Cost</label>
            <input type="text" id="total_cost" name="total_cost" class="form-control" readonly>
            @error('total_cost') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save Entry</button>
    </form>

    <hr>

    <h3>Existing Lunch Entries</h3>

    @if($entries->isEmpty())
        <p>No lunch entries found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Meal Type</th>
                    <th>Meal Price</th>
                    <th>Count</th>
                    <th>Total Cost</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d') }}</td>
                    <td>{{ $entry->day_name }}</td>
                    <td>{{ $entry->meal_type }}</td>
                    <td>{{ number_format($entry->meal_price, 2) }}</td>
                    <td>{{ $entry->count }}</td>
                    <td>{{ number_format($entry->total_cost, 2) }}</td>
                    <td>
    <a href="{{ route('daily-lunch.edit', $entry->id) }}" class="btn btn-sm btn-warning">Edit</a>

    <form action="{{ route('daily-lunch.destroy', $entry->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</button>
    </form>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
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

            // Recalculate total cost
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
