@extends('layouts.app')

@section('title', 'Daily Lunch Entry')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4" style="font-weight: 600;">Daily Lunch Entry</h2>

                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="dailyLunchForm" method="POST" action="{{ route('daily-lunch.store') }}">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="entry_date" class="form-label">Select Date</label>
                                <input type="date" name="entry_date" id="entry_date" class="form-control" required value="{{ old('entry_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="day_name" class="form-label">Day</label>
                                <input type="text" id="day_name" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="meal_type" class="form-label">Meal Type</label>
                                <input type="text" name="meal_type" id="meal_type" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="meal_price" class="form-label">Meal Price (₹)</label>
                                <input type="text" name="meal_price" id="meal_price" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="meal_count" class="form-label">Meal Count</label>
                                <input type="number" name="meal_count" id="meal_count" class="form-control" min="0" value="{{ old('meal_count', 0) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="total_cost" class="form-label">Total Cost (₹)</label>
                                <input type="text" name="total_cost" id="total_cost" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-3">Save Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Existing Entries --}}
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h3 class="card-title mb-3" style="font-weight: 600;">Existing Daily Lunch Entries</h3>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Meal Type</th>
                                    <th>Meal Price (₹)</th>
                                    <th>Meal Count</th>
                                    <th>Total Cost (₹)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('l') }}</td>
                                        <td>{{ ucfirst($entry->meal_type) }}</td>
                                        <td>{{ number_format($entry->meal_price, 2) }}</td>
                                        <td>{{ $entry->meal_count }}</td>
                                        <td>{{ number_format($entry->total_cost, 2) }}</td>
                                        <td class="d-flex gap-2">
                                            <a href="{{ route('daily-lunch.edit', $entry->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('daily-lunch.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Delete this entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No daily lunch entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS Logic --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const entryDateInput = document.getElementById('entry_date');
    const dayNameInput = document.getElementById('day_name');
    const mealTypeInput = document.getElementById('meal_type');
    const mealPriceInput = document.getElementById('meal_price');
    const mealCountInput = document.getElementById('meal_count');
    const totalCostInput = document.getElementById('total_cost');

    function updateTotalCost() {
        const price = parseFloat(mealPriceInput.value);
        const count = parseInt(mealCountInput.value);
        if (!isNaN(price) && !isNaN(count)) {
            totalCostInput.value = (price * count).toFixed(2);
        } else {
            totalCostInput.value = '';
        }
    }

    entryDateInput.addEventListener('change', function () {
        const selectedDate = this.value;
        if (!selectedDate) return;

        fetch("{{ route('daily-lunch.get-meal-info') }}?date=" + selectedDate)
            .then(response => {
                if (!response.ok) throw new Error('No meal plan or pricing data found for selected date');
                return response.json();
            })
            .then(data => {
                dayNameInput.value = data.day;
                mealTypeInput.value = data.meal_type.charAt(0).toUpperCase() + data.meal_type.slice(1);
                mealPriceInput.value = data.price;
                mealCountInput.value = 0;
                totalCostInput.value = '';
            })
            .catch(err => {
                alert(err.message);
                dayNameInput.value = '';
                mealTypeInput.value = '';
                mealPriceInput.value = '';
                mealCountInput.value = 0;
                totalCostInput.value = '';
            });
    });

    mealCountInput.addEventListener('input', updateTotalCost);
});
</script>
@endsection
