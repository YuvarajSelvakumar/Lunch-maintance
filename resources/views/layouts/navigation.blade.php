<style>
  .nav-link {
    transition: color 0.3s ease, border-bottom 0.3s ease;
  }

  .nav-link:hover {
    color:rgb(8, 8, 8) !important; /* Bootstrap warning yellow */
    border-bottom: 2px solidrgb(87, 66, 5);
  }

  .nav-link.active {
    color:rgb(7, 189, 255) !important;
    border-bottom: 3px solid #ffc107;
    font-weight: bold;
  }
nav.navbar.navbar-expand-lg.navbar-dark.shadow-sm {
    background: linear-gradient(to right, rgb(7 11 24), #8faaf6);
}

</style>

<nav class="navbar navbar-expand-lg navbar-dark  shadow-sm">
  <div class="container m-auto">
     <img src="{{ asset('image/logo.png') }}" alt="Logo" style="height: 30px;">
    <a class="navbar-brand" href="{{ route('menu-pricing.index') }}">
    
    </a>

    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation Links -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav m-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('menu-pricing.*') ? 'active' : '' }}" href="{{ route('menu-pricing.index') }}">Menu Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('weekly-menu.*') ? 'active' : '' }}" href="{{ route('weekly-menu.index') }}">Weekly Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('daily-lunch.*') ? 'active' : '' }}" href="{{ route('daily-lunch.index') }}">Daily Lunch</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('monthly-summary.*') ? 'active' : '' }}" href="{{ route('monthly-summary.index') }}">Monthly Summary</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('vendor-payment.*') ? 'active' : '' }}" href="{{ route('vendor-payment.index') }}">Vendor Payment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('revision-history.*') ? 'active' : '' }}" href="{{ route('revision-history.index') }}">Revision History</a>
        </li>
      </ul>

      <!-- User Dropdown -->
       @auth
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
