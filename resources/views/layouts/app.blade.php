<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Lunch Maintenance Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Lunch Maintenance</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a href="{{ route('menu-pricing.index') }}" class="nav-link">Menu Pricing</a></li>
                    <li class="nav-item"><a href="{{ route('weekly-menu.index') }}" class="nav-link">Weekly Menu</a></li>
                    <li class="nav-item">
    <a class="nav-link" href="{{ route('daily-lunch.index') }}">Daily Lunch Entry</a>
</li>

                    <li class="nav-item"><a href="{{ route('monthly-summary.index') }}" class="nav-link">Monthly Summary</a></li>
                    <li class="nav-item"><a href="{{ route('vendor-payment.index') }}" class="nav-link">Vendor Payment</a></li>
                    <li class="nav-item"><a href="{{ route('revision-history.index') }}" class="nav-link">Revision History</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
