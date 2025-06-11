@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <!-- Main Form Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Menu Pricing</h2>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form method="POST" action="{{ route('menu-pricing.store') }}">
            @csrf
            <div class="row g-4">
              @foreach(['veg'=>'Veg','egg'=>'Egg','chicken'=>'Chicken'] as $field => $label)
              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">{{ $label }} Price</label>
                <input type="number" name="{{ $field }}_price"
                       class="form-control @error($field.'_price') is-invalid @enderror"
                       required min="1" step="1" value="{{ old($field.'_price') }}">
                @error($field.'_price')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              @endforeach

              <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Effective From</label>
                <input type="date" name="effective_from"
                       class="form-control @error('effective_from') is-invalid @enderror"
                       required value="{{ old('effective_from') }}"
                       min="{{ $minDate }}" max="{{ $maxDate }}">
                @error('effective_from')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mt-5 d-flex justify-content-center">
              <button type="submit" class="btn btn-primary px-5">Save Pricing</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Existing Pricings Card -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h3 class="card-title text-center mb-3">Existing Menu Pricings</h3>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr><th>Veg</th><th>Egg</th><th>Chicken</th><th>Version</th><th>Effective From</th></tr>
              </thead>
              <tbody>
                @forelse ($menuPricings as $pricing)
                  <tr>
                    <td>{{ number_format($pricing->veg_price,2) }}</td>
                    <td>{{ number_format($pricing->egg_price,2) }}</td>
                    <td>{{ number_format($pricing->chicken_price,2) }}</td>
                    <td>{{ $pricing->version }}</td>
                    <td>{{ \Carbon\Carbon::parse($pricing->effective_from)->format('d M Y') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center">No menu pricing found</td></tr>
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
  border-radius: 0.75rem;
  box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
  transition: transform .3s ease, box-shadow .3s ease;
}
.premium-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
}
.premium-card .card-body {
  padding: 2rem;
}
.premium-card .card-title {
  font-size: 1.75rem;
  font-weight: 600;
}
.table-hover tbody tr:hover {
  background-color: rgba(0,123,255,0.05);
}
</style>
@endpush
