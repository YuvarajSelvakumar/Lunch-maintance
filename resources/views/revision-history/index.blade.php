@extends('layouts.app')

@section('title', 'Revision History')

@section('content')
<div class="container-fluid mt-4">
  <!-- Header & Filter Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Revision History for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h2>
          
          <form method="GET" action="{{ route('revision-history.index') }}" class="row g-3 align-items-center mb-3">
            <div class="col-auto">
              <label class="form-label" for="type">Select Type:</label>
              <select name="type" id="type" class="form-select">
                <option value="menu_pricing" {{ $type=='menu_pricing'?'selected':'' }}>Menu Pricing</option>
                <option value="weekly_menu" {{ $type=='weekly_menu'?'selected':'' }}>Weekly Menu</option>
                <option value="vendor_payment" {{ $type=='vendor_payment'?'selected':'' }}>Vendor Payment</option>
                <option value="daily_lunch_entry" {{ $type=='daily_lunch_entry'?'selected':'' }}>Daily Lunch Entry</option>
              </select>
            </div>
            <div class="col-auto">
              <label class="form-label" for="month">Select Month:</label>
              <input type="month" name="month" id="month" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-primary mt-3">Filter</button>
            </div>
          </form>

          @if($revisions->isEmpty())
            <div class="alert alert-info">No revision records found for selected type and month.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Results Card -->
  @if($revisions->isNotEmpty())
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          @switch($type)
            @case('menu_pricing')
              <h3 class="card-title mb-3">Menu Pricing Revisions</h3>
              @break
            @case('weekly_menu')
              <h3 class="card-title mb-3">Weekly Menu Revisions</h3>
              @break
            @case('vendor_payment')
              <h3 class="card-title mb-3">Vendor Payment Revisions</h3>
              @break
            @case('daily_lunch_entry')
              <h3 class="card-title mb-3">Daily Lunch Entry Revisions</h3>
              @break
          @endswitch

          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="table-light">
                @if($type=='menu_pricing')
                <tr>
                  <th>Veg Price</th><th>Egg Price</th><th>Chicken Price</th>
                  <th>Effective From</th><th>Created At</th>
                </tr>
                @elseif($type=='weekly_menu')
                <tr>
                  <th>Day</th><th>Meal Type</th><th>Month</th><th>Created At</th>
                </tr>
                @elseif($type=='vendor_payment')
                <tr>
                  <th>Month</th><th>Total Amount</th><th>Paid</th><th>Balance</th>
                  <th>Status</th><th>Last Payment</th><th>Created At</th>
                </tr>
                @elseif($type=='daily_lunch_entry')
                <tr>
                  <th>Date</th><th>Meal Type</th><th>Count</th><th>Total Cost</th><th>Created At</th>
                </tr>
                @endif
              </thead>
              <tbody>
                @foreach($revisions as $r)
                <tr>
                  @if($type=='menu_pricing')
                    <td>₹{{ number_format($r->veg_price,2) }}</td>
                    <td>₹{{ number_format($r->egg_price,2) }}</td>
                    <td>₹{{ number_format($r->chicken_price,2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->effective_from)->format('d M Y') }}</td>
                    <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                  @elseif($type=='weekly_menu')
                    <td>{{ $r->day_of_week }}</td>
                    <td>{{ $r->meal_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->month.'-01')->format('F Y') }}</td>
                    <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                  @elseif($type=='vendor_payment')
                    <td>{{ \Carbon\Carbon::parse($r->month.'-01')->format('F Y') }}</td>
                    <td>₹{{ number_format($r->total_amount,2) }}</td>
                    <td>₹{{ number_format($r->paid_amount,2) }}</td>
                    <td>₹{{ number_format($r->balance,2) }}</td>
                    <td>{{ $r->status }}</td>
                    <td>{{ optional($r->payment_date)->format('d M Y') }}</td>
                    <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                  @elseif($type=='daily_lunch_entry')
                    <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                    <td>{{ $r->meal_type }}</td>
                    <td>{{ $r->count }}</td>
                    <td>₹{{ number_format($r->total_cost,2) }}</td>
                    <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
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
