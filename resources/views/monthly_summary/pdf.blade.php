<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Summary - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px 12px; text-align: center; }
        th { background-color: #f2f2f2; }
        .total-row td { font-weight: bold; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Monthly Lunch Summary - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h2>
    <table>
        <thead>
            <tr>
                <th>Meal Type</th>
                <th>Total Count</th>
                <th>Total Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Veg</td>
                <td>{{ $summary['total_veg'] }}</td>
                <td>₹{{ number_format($summary['total_veg_cost'], 2) }}</td>
            </tr>
            <tr>
                <td>Egg</td>
                <td>{{ $summary['total_egg'] }}</td>
                <td>₹{{ number_format($summary['total_egg_cost'], 2) }}</td>
            </tr>
            <tr>
                <td>Chicken</td>
                <td>{{ $summary['total_chicken'] }}</td>
                <td>₹{{ number_format($summary['total_chicken_cost'], 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2">Total Cost</td>
                <td>₹{{ number_format($summary['total_cost'], 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
