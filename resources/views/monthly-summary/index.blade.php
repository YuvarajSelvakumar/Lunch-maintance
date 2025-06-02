@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Monthly Lunch Summary</h2>

    <form method="GET" action="{{ route('monthly-summary.index') }}" class="mb-3">
        <label for="month">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ date('Y-m', strtotime($month)) }}">
        <button type="submit" class="btn btn-primary btn-sm">View</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Meal Type</th>
                <th>Total Count</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Veg</td>
                <td>{{ $summary['total_veg'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Egg</td>
                <td>{{ $summary['total_egg'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Chicken</td>
                <td>{{ $summary['total_chicken'] ?? 0 }}</td>
            </tr>
            <tr>
                <th>Total Cost</th>
                <th>{{ number_format($summary['total_cost'] ?? 0, 2) }}</th>
            </tr>
        </tbody>
    </table>
</div>
@endsection
