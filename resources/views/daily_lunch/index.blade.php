@extends('layouts.app')

@section('title', 'Daily Lunch Entry')

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Add Daily Lunch Entry</h2>
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form method="POST" action="{{ route('daily-lunch.store') }}">
            @csrf
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="entry_date"
                       class="form-control @error('entry_date') is-invalid @enderror" required>
                @error('entry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Day</label>
                <input type="text" id="day_name" name="day_name"
                       class="form-control" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Meal Type</label>
                <input type="text" id="meal_type" name="meal_type"
                       class="form-control" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Meal Price</label>
                <input type="text" id="meal_price" name="meal_price"
                       class="form-control" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Count</label>
                <input type="number" id="count" name="count"
                       class="form-control @error('count') is-invalid @enderror"
                       min="1" required>
                @error('count') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Total Cost</label>
                <input type="text" id="total_cost" name="total_cost"
                       class="form-control" readonly>
              </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
              <button type="submit" class="btn btn-primary px-5">Save Entry</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Existing Lunch Entries -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h3 class="card-title text-center mb-4">Existing Lunch Entries</h3>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Date</th><th>Day</th><th>Meal Type</th><th>Meal Price</th>
                  <th>Count</th><th>Total Cost</th><th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($entries as $entry)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d') }}</td>
                  <td>{{ $entry->day_name }}</td>
                  <td>{{ $entry->meal_type }}</td>
                  <td>{{ number_format($entry->meal_price,2) }}</td>
                  <td>{{ $entry->count }}</td>
                  <td>{{ number_format($entry->total_cost,2) }}</td>
                  <td class="d-flex gap-1">
                    <a href="{{ route('daily-lunch.edit', $entry->id) }}"
                       class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('daily-lunch.destroy', $entry->id) }}"
                          method="POST" onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center">No lunch entries found.</td>
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
@endsection

@push('styles')
<style>
.premium-card {
  border: none;
  border-radius: .75rem;
  box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1);
  transition: transform .3s ease, box-shadow .3s ease;
}
.premium-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 2rem rgba(0,0,0,.15);
}
.premium-card .card-body {
  padding: 2rem;
}
.premium-card .card-title {
  font-size: 1.75rem;
  font-weight: 600;
}
.table-hover tbody tr:hover {
  background-color: rgba(0,123,255,.05);
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
    try {
      const res = await fetch(`{{ route('daily-lunch.get-meal-info') }}?date=${selectedDate}`);
      const data = await res.json();
      document.getElementById('day_name').value = data.day_name || '';
      document.getElementById('meal_type').value = data.meal_type || '';
      mealPriceInput.value = data.price || '';
      totalCostInput.value = (data.price || 0) * (parseInt(countInput.value) || 0);
    } catch (e) { console.error(e); }
  }

  dateInput.addEventListener('change', () => {
    if (dateInput.value) fetchMealInfo(dateInput.value);
  });
  countInput.addEventListener('input', () => {
    totalCostInput.value = (parseFloat(mealPriceInput.value) || 0) * (parseInt(countInput.value) || 0);
  });

  if (dateInput.value) fetchMealInfo(dateInput.value);
});
</script>
@endpush
