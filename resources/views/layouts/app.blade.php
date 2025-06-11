<!DOCTYPE html>

<html lang="en">

<head>
 <meta charset="UTF-8" />
 <title>Lunch Maintenance Module</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>

<style>
.nav-link.active {
 color:rgb(174, 230, 196) !important; /* yellow (or any custom color) */
 font-weight: bold;
 }
 </style>

<body>
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">

<div class="container">

<a class="navbar-brand" href="{{ url('/') }}">Lunch Maintenance</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
 aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
 <span class="navbar-toggler-icon"></span>
 </button>


 <div class="collapse navbar-collapse" id="navbarContent">

<ul class="navbar-nav me-auto mb-2 mb-lg-0">

<li class="nav-item">

<a href="{{ route('menu-pricing.index') }}"

class="nav-link {{ Request::routeIs('menu-pricing.index') ? 'active' : '' }}">

Menu Pricing

</a>

</li>

<li class="nav-item">
<a href="{{ route('weekly-menu.index') }}"

class="nav-link {{ Request::routeIs('weekly-menu.index') ? 'active' : '' }}">

Weekly Menu
 </a>

</li>

<li class="nav-item">

<a href="{{ route('daily-lunch.index') }}"

class="nav-link {{ Request::routeIs('daily-lunch.index') ? 'active' : '' }}">

Daily Lunch Entry

</a>

 </li>

<li class="nav-item">

<a href="{{ route('monthly-summary.index') }}"

class="nav-link {{ Request::routeIs('monthly-summary.index') ? 'active' : '' }}">

Monthly Summary
 </a>

</li>
 <li class="nav-item">
 <a href="{{ route('vendor-payment.index') }}"

class="nav-link {{ Request::routeIs('vendor-payment.index') ? 'active' : '' }}">

Vendor Payment
</a>

</li>

<li class="nav-item">

<a href="{{ route('revision-history.index') }}"

class="nav-link {{ Request::routeIs('revision-history.index') ? 'active' : '' }}">

Revision History

</a>

</li>

</ul>

</div>

 </div>

</nav>

 <div class="container">
 @yield('content')
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>