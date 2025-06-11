@extends('layouts.app')

@section('title', 'Vendor Payment')

@section('content')
<div class="container-fluid mt-4">
  <!-- Filter & Payment Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-9">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Vendor Payment</h2>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form method="GET" action="{{ route('vendor-payment.index') }}" class="row g-2 align-items-end mb-4">
            <div class="col-md-6">
              <label class="form-label">Select Month:</label>
              <select name="month" class="form-select" onchange="this.form.submit()">
                @foreach ($availableMonths as $available)
                  @php $val = \Carbon\Carbon::parse($available)->format('Y-m'); @endphp
                  <option value="{{ $val }}" {{ $val === $monthInput ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($available)->format('F Y') }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-auto">
              <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            <div class="col-md-auto">
              <a href="{{ route('vendor-payment.refresh', ['month' => $monthInput]) }}"
                 class="btn btn-warning w-100">🔁 Refresh</a>
            </div>
          </form>

          @if($payment)
            <form method="POST" action="{{ route('vendor-payment.update', $payment->id) }}">
              @csrf
              <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Month</th><th>Total Cost</th><th>Paid</th><th>Balance</th>
                      <th>Status</th><th>New Payment</th><th>Date</th><th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                      <td>₹{{ number_format($payment->total_amount,2) }}</td>
                      <td>₹{{ number_format($payment->paid_amount,2) }}</td>
                      <td>₹{{ number_format($payment->balance,2) }}</td>
                      <td>{{ $payment->status }}</td>
                      <td><input type="number" step="0.01" name="paid_amount" class="form-control" required></td>
                      <td><input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}"></td>
                      <td><button type="submit" class="btn btn-success btn-sm">Save</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </form>
          @endif

          <div class="alert alert-info">
            <strong>Total Outstanding Balance: ₹{{ number_format($totalBalance,2) }}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor Payments List -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-9">
      <div class="card premium-card">
        <div class="card-body">
          <h3 class="card-title mb-4">All Vendor Payments</h3>
          <div class="table-responsive">
            <table class="table table-striped align-middle table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Month</th><th>Total</th><th>Paid</th><th>Balance</th>
                  <th>Status</th><th>Last Payment</th><th>Entries</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($allPayments as $vp)
                  <tr data-bs-toggle="collapse" data-bs-target="#entries-{{ $vp->id }}" style="cursor:pointer;">
                    <td>{{ \Carbon\Carbon::parse($vp->month)->format('F Y') }}</td>
                    <td>₹{{ number_format($vp->total_amount,2) }}</td>
                    <td>₹{{ number_format($vp->paid_amount,2) }}</td>
                    <td>₹{{ number_format($vp->balance,2) }}</td>
                    <td>{{ $vp->status }}</td>
                    <td>{{ optional($vp->payment_date)->format('d-m-Y') ?? '-' }}</td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary">View</button>
                    </td>
                  </tr>
                  <tr class="collapse" id="entries-{{ $vp->id }}">
                    <td colspan="7">
                      <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                          <thead class="table-light">
                            <tr><th>#</th><th>Paid Amount</th><th>Payment Date</th></tr>
                          </thead>
                          <tbody>
                            @forelse($vp->entries()->orderBy('payment_date')->get() as $i => $entry)
                              <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>₹{{ number_format($entry->paid_amount,2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($entry->payment_date)->format('d-m-Y') }}</td>
                              </tr>
                            @empty
                              <tr><td colspan="3"><em>No entries this month.</em></td></tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center">No vendor payments found.</td></tr>
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
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1);
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,.15);
  }
  .premium-card .card-body { padding: 2rem; }
  .premium-card .card-title { font-size: 1.75rem; font-weight: 600; margin-bottom: 1rem; }
  .table-hover tbody tr:hover { background-color: rgba(0,123,255,.05); }
</style>
@endpush

@push('scripts')
<script>
  // No additional JS needed — collapse on row click handled by Bootstrap collapse
</script>
@endpush
