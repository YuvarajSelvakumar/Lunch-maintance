@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Revision History</h2>

    <form method="GET" action="{{ route('revision-history.index') }}" class="mb-3">
        <label for="type">Select Type:</label>
        <select name="type" id="type" class="form-select" style="width: auto; display: inline-block;">
            <option value="menu_pricing" {{ $type == 'menu_pricing' ? 'selected' : '' }}>Menu Pricing</option>
            <option value="weekly_menu" {{ $type == 'weekly_menu' ? 'selected' : '' }}>Weekly Menu</option>
            <option value="vendor_payment" {{ $type == 'vendor_payment' ? 'selected' : '' }}>Vendor Payment</option>
        </select>

        <label for="month" class="ms-3">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ date('Y-m', strtotime($month)) }}">

        <button type="submit" class="btn btn-primary btn-sm ms-3">View</button>
    </form>

    @if($revisions->isEmpty())
        <p>No revision data found for selected criteria.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    @if($type == 'vendor_payment')
                        <th>Month</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Arrears</th>
                        <th>Payment Date</th>
                        <th>Created At</th>
                    @else
                        <th>Version</th>
                        <th>Effective From</th>
                        <th>Details</th>
                        <th>Created At</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($revisions as $rev)
                    <tr>
                        @if($type == 'vendor_payment')
                            <td>{{ date('F Y', strtotime($rev->month)) }}</td>
                            <td>{{ number_format($rev->total_amount, 2) }}</td>
                            <td>{{ number_format($rev->amount_paid, 2) }}</td>
                            <td>{{ number_format($rev->arrears, 2) }}</td>
                            <td>{{ $rev->payment_date ? date('d-M-Y', strtotime($rev->payment_date)) : 'Not Paid Yet' }}</td>
                            <td>{{ $rev->created_at->format('d-M-Y H:i') }}</td>
                        @else
                            <td>{{ $rev->version }}</td>
                            <td>{{ date('d-M-Y', strtotime($rev->effective_from)) }}</td>
                            <td>
                                @if($type == 'menu_pricing')
                                    Veg: {{ $rev->veg_price }} |
                                    Egg: {{ $rev->egg_price }} |
                                    Chicken: {{ $rev->chicken_price }}
                                @elseif($type == 'weekly_menu')
                                    Month: {{ date('F Y', strtotime($rev->month)) }} |
                                    Day: {{ $rev->day_of_week }} |
                                    Meal: {{ $rev->meal_type }}
                                @endif
                            </td>
                            <td>{{ $rev->created_at->format('d-M-Y H:i') }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
