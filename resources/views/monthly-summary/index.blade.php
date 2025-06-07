@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px;">
    <h2 class="mb-4">Monthly Summary</h2>

    {{-- Month Selection --}}
    <form method="GET" action="{{ route('monthly-summary.index') }}" class="mb-4 row g-3 align-items-end">
        <div class="col-auto">
            <label for="month" class="form-label">Select Month</label>
            <input type="month" name="month" id="month" class="form-control" value="{{ \Carbon\Carbon::parse($month)->format('Y-m') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    {{-- Pricing Table --}}
    @if(session('errors'))
        <div class="alert alert-danger">{{ session('errors')->first('month') }}</div>
    @elseif($summary)
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Meal Type</th>
                    <th>Total Count</th>
                    <th>Unit Price (₹)</th>
                    <th>Total Cost (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Veg</td>
                    <td>{{ $summary['total_veg'] }}</td>
                    <td>{{ $pricing->veg_price }}</td>
                    <td>{{ $summary['total_veg'] * $pricing->veg_price }}</td>
                </tr>
                <tr>
                    <td>Egg</td>
                    <td>{{ $summary['total_egg'] }}</td>
                    <td>{{ $pricing->egg_price }}</td>
                    <td>{{ $summary['total_egg'] * $pricing->egg_price }}</td>
                </tr>
                <tr>
                    <td>Chicken</td>
                    <td>{{ $summary['total_chicken'] }}</td>
                    <td>{{ $pricing->chicken_price }}</td>
                    <td>{{ $summary['total_chicken'] * $pricing->chicken_price }}</td>
                </tr>
                <tr class="table-info fw-bold">
                    <td colspan="3" class="text-end">Total Monthly Cost</td>
                    <td>₹{{ $summary['total_cost'] }}</td>
                </tr>
            </tbody>
        </table>

    <a href="{{ route('monthly-summary.exportExcel', ['month' => $month]) }}" class="btn btn-outline-success">Export as Excel</a>
<a href="{{ route('monthly-summary.exportPdf', ['month' => $month]) }}" class="btn btn-outline-danger">Export as PDF</a>

    @endif
</div>
@endsection
