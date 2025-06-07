<!DOCTYPE html>
<html>
<head>
    <title>Monthly Summary PDF - {{ $month }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #000; padding: 8px; text-align: left;}
        th { background-color: #f0f0f0; }
        .total { font-weight: bold; background-color: #d0e9ff; }
    </style>
</head>
<body>
    <h2>Monthly Summary for {{ $month }}</h2>
    <table>
        <thead>
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
            <tr class="total">
                <td colspan="3" style="text-align:right;">Total Monthly Cost</td>
                <td>₹{{ $summary['total_cost'] }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
