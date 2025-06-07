@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Vendor Payment</h2>

    <!-- Month Selector -->
    <form method="GET" action="{{ route('vendor-payment.index') }}" class="mb-4">
        <label>Select Month:</label>
        <input type="month" name="month" value="{{ \Carbon\Carbon::parse($month)->format('Y-m') }}">
        <button type="submit" class="btn btn-primary">View</button>
    </form>

    <!-- Payment Form -->
    <form action="{{ route('vendor-payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Cost</th>
                    <th>Paid Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>New Payment</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                    <td>₹{{ number_format($payment->total_amount, 2) }}</td>
                    <td>₹{{ number_format($payment->paid_amount, 2) }}</td>
                    <td>₹{{ number_format($payment->balance, 2) }}</td>
                    <td>{{ $payment->status }}</td>
                    <td><input type="number" step="0.01" name="paid_amount" class="form-control" required></td>
                    <td><input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}"></td>
                    <td><button type="submit" class="btn btn-success">Save</button></td>
                </tr>
            </tbody>
        </table>
    </form>

    <div class="alert alert-info">
        <strong>Total Outstanding Balance (All Months): ₹{{ number_format($totalBalance, 2) }}</strong>
    </div>

    <!-- List of All Payments -->
    <h4 class="mt-5">All Vendor Payments</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Month</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allPayments as $vp)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($vp->month)->format('F Y') }}</td>
                    <td>₹{{ number_format($vp->total_amount, 2) }}</td>
                    <td>₹{{ number_format($vp->paid_amount, 2) }}</td>
                    <td>₹{{ number_format($vp->balance, 2) }}</td>
                    <td>{{ $vp->status }}</td>
                    <td>{{ optional($vp->payment_date)->format('d-m-Y') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
