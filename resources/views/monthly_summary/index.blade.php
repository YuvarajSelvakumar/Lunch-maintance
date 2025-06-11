@extends('layouts.app')

@section('title', 'Monthly Summary')

@section('content')
<div class="container-fluid mt-4">
  <!-- Filter & Export Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Monthly Summary</h2>

          <form method="GET" action="{{ route('monthly-summary.index') }}" class="row g-3 align-items-end mb-3">
            <div class="col-md-6 col-12">
              <label for="month" class="form-label">Select Month:</label>
              <select name="month" id="month" class="form-select" required>
                @foreach ($availableMonths as $m)
                  <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($m . '-01')->format('F Y') }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 col-12">
              <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
          </form>

          <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('monthly-summary.exportExcel', ['month' => $month]) }}"
               class="btn btn-outline-success flex-grow-1 flex-md-grow-0">
              Export as Excel
            </a>
            <a href="{{ route('monthly-summary.exportPdf', ['month' => $month]) }}"
               class="btn btn-outline-danger flex-grow-1 flex-md-grow-0">
              Export as PDF
            </a>
          </div>

          @if(isset($message))
            <div class="alert alert-warning">{{ $message }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Data Cards per Month Summary -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      @foreach($monthlySummaries as $summaryMonth => $monthlySummary)
        <div class="card premium-card mb-4">
          <div class="card-body">
            <h3 class="card-title">{{ \Carbon\Carbon::parse($summaryMonth . '-01')->format('F Y') }}</h3>
            <div class="table-responsive">
              <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Meal Type</th>
                    <th>Total Count</th>
                    <th>Total Cost</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach (['veg', 'egg', 'chicken'] as $type)
                    <tr>
                      <td>{{ ucfirst($type) }}</td>
                      <td>{{ $monthlySummary[$type]['total_count'] ?? 0 }}</td>
                      <td>₹{{ number_format($monthlySummary[$type]['total_cost'] ?? 0, 2) }}</td>
                    </tr>
                  @endforeach
                  <tr class="fw-bold">
                    <td colspan="2">Grand Total</td>
                    <td>₹{{ number_format(collect($monthlySummary)->sum('total_cost'), 2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endforeach
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
  .premium-card .card-body { padding: 2rem; }
  .premium-card .card-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; }
  .table-hover tbody tr:hover { background-color: rgba(0,123,255,.05); }
</style>
@endpush
