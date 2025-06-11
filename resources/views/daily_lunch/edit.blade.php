@extends('layouts.app')

@section('title', 'Edit Daily Lunch Entry')

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Edit Daily Lunch Entry</h2>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form method="POST" action="{{ route('daily-lunch.update', $entry->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
              @php $today = \Carbon\Carbon::today()->format('Y-m-d'); @endphp

              <div class="col-12 col-md-6">
                <label class="form-label" for="date">Date</label>
                <input type="date" id="date" name="entry_date"
                  class="form-control @error('entry_date') is-invalid @enderror"
                  required min="{{ $today }}" max="{{ $today }}"
                  value="{{ old('entry_date', $today) }}">
                @error('entry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Day</label>
                <input type="text" id="day_name" name="day_name"
                  class="form-control" value="{{ $entry->day_name }}" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Meal Type</label>
                <input type="text" id="meal_type" name="meal_type"
                  class="form-control" value="{{ $entry->meal_type }}" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Meal Price</label>
                <input type="text" id="meal_price" name="meal_price"
                  class="form-control" value="{{ $entry->meal_price }}" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label" for="count">Count</label>
                <input type="number" id="count" name="count"
                  class="form-control @error('count') is-invalid @enderror"
                  value="{{ $entry->count }}" min="1" required>
                @error('count') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Total Cost</label>
                <input type="text" id="total_cost" name="total_cost"
                  class="form-control" value="{{ $entry->total_cost }}" readonly>
              </div>
            </div>

            <div class="mt-4 d-flex flex-column flex-md-row justify-content-center gap-2">
              <button type="submit" class="btn btn-primary w-100 w-md-25">Update Entry</button>
              <a href="{{ route('daily-lunch.create') }}" class="btn btn-secondary w-100 w-md-25">Back</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .premium-card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
  }
  .premium-card .card-body { padding: 2rem; }
  .premium-card .card-title {
    font-size: 1.75rem;
    font-weight: 600;
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const countInput = document.getElementById('count');
    const mealPriceInput = document.getElementById('meal_price');
    const totalCostInput = document.getElementById('total_cost');

    async function fetchMealInfo(selectedDate) {
      const res = await fetch(`{{ route('daily-lunch.get-meal-info') }}?date=${selectedDate}`);
      const data = await res.json();
      document.getElementById('day_name').value = data.day_name || '';
      document.getElementById('meal_type').value = data.meal_type || '';
      mealPriceInput.value = data.price ?? '';
      totalCostInput.value = (data.price || 0) * (parseInt(countInput.value) || 0);
    }

    dateInput.addEventListener('change', () => {
      if (dateInput.value) fetchMealInfo(dateInput.value);
    });
    countInput.addEventListener('input', () => {
      totalCostInput.value =
        (parseFloat(mealPriceInput.value) || 0) *
        (parseInt(countInput.value) || 0);
    });

    if (dateInput.value) fetchMealInfo(dateInput.value);
  });
</script>
@endpush
