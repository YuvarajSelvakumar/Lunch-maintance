@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Revision History for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h2>

    <!-- Filter form -->
    <form method="GET" action="{{ route('revision-history.index') }}" class="mb-4 d-flex align-items-center gap-3">
        <label for="type" class="mb-0">Select Type:</label>
        <select name="type" id="type" class="form-select" style="width: 200px;">
            <option value="menu_pricing" {{ $type == 'menu_pricing' ? 'selected' : '' }}>Menu Pricing</option>
            <option value="weekly_menu" {{ $type == 'weekly_menu' ? 'selected' : '' }}>Weekly Menu</option>
            <option value="vendor_payment" {{ $type == 'vendor_payment' ? 'selected' : '' }}>Vendor Payment</option>
            <option value="daily_lunch_entry" {{ $type == 'daily_lunch_entry' ? 'selected' : '' }}>Daily Lunch Entry</option>
        </select>

        <label for="month" class="mb-0">Select Month:</label>
        <input type="month" name="month" id="month" class="form-control" value="{{ $month }}" style="width: 200px;">

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @if($revisions->isEmpty())
        <div class="alert alert-info">No revision records found for selected type and month.</div>
    @else
        @switch($type)
            @case('menu_pricing')
                <h4>Menu Pricing Revisions</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Veg Price</th>
                            <th>Egg Price</th>
                            <th>Chicken Price</th>
                            <th>Effective Month</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revisions as $pricing)
                        <tr>
                            <td>{{ $pricing->version }}</td>
                            <td>₹{{ number_format($pricing->veg_price, 2) }}</td>
                            <td>₹{{ number_format($pricing->egg_price, 2) }}</td>
                            <td>₹{{ number_format($pricing->chicken_price, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pricing->month)->format('F Y') }}</td>
                            <td>{{ $pricing->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @break

            @case('weekly_menu')
                <h4>Weekly Menu Revisions</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Day</th>
                            <th>Meal Type</th>
                            <th>Count</th>
                            <th>Month</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revisions as $menu)
                        <tr>
                            <td>{{ $menu->version }}</td>
                            <td>{{ $menu->day }}</td>
                            <td>{{ $menu->meal_type }}</td>
                            <td>{{ $menu->count }}</td>
                            <td>{{ \Carbon\Carbon::parse($menu->month)->format('F Y') }}</td>
                            <td>{{ $menu->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @break

            @case('vendor_payment')
                <h4>Vendor Payment Revisions</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Last Payment Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revisions as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->month)->format('F Y') }}</td>
                            <td>₹{{ number_format($payment->total_amount, 2) }}</td>
                            <td>₹{{ number_format($payment->paid_amount, 2) }}</td>
                            <td>₹{{ number_format($payment->balance, 2) }}</td>
                            <td>{{ $payment->status }}</td>
                            <td>{{ optional($payment->payment_date)->format('d M Y') ?? '-' }}</td>
                            <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @break

            @case('daily_lunch_entry')
                <h4>Daily Lunch Entry Revisions</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Meal Type</th>
                            <th>Count</th>
                            <th>Total Cost</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revisions as $entry)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                            <td>{{ $entry->meal_type }}</td>
                            <td>{{ $entry->count }}</td>
                            <td>₹{{ number_format($entry->total_cost, 2) }}</td>
                            <td>{{ $entry->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @break

            @default
                <div class="alert alert-warning">Unknown revision type.</div>
        @endswitch
    @endif
</div>
@endsection
