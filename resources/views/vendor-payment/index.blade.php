@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Vendor Payment</h2>

    <form method="GET" action="{{ route('vendor-payment.index') }}" class="mb-3">
        <label for="month">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ date('Y-m', strtotime($month)) }}">
        <button type="submit" class="btn btn-primary btn-sm">View</button>
    </form>

    @if($payment)
        <table class="table table-bordered">
            <tr>
                <th>Month</th>
                <td>{{ date('F Y', strtotime($payment->month)) }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>{{ number_format($payment->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Amount Paid</th>
                <td>{{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <th>Arrears</th>
                <td>{{ number_format($payment->arrears, 2) }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ $payment->payment_date ? date('d-M-Y', strtotime($payment->payment_date)) : 'Not Paid Yet' }}</td>
            </tr>
        </table>
    @else
        <p>No payment data found for this month.</p>
    @endif
</div>
@endsection
