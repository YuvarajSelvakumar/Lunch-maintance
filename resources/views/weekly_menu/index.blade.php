@extends('layouts.app')

@section('title', 'Weekly Menu')

@section('content')
<div class="container-fluid mt-4">
  <!-- Selector Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">
            Weekly Menu for {{ \Carbon\Carbon::parse($month)->format('F Y') }}
          </h2>

          {{-- Month Selector --}}
          <form method="GET" action="{{ route('weekly-menu.index') }}" class="mb-4">
            <div class="row g-2 align-items-center">
              <div class="col-auto">
                <input type="month" name="month" value="{{ $month }}"
                  class="form-control" required
                  min="{{ $minMonthForView }}" max="{{ $maxMonthForView }}">
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary">Load</button>
              </div>
            </div>
          </form>
          @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Menu & Prices Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <form method="POST" action="{{ route('weekly-menu.store') }}">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">

            <div class="table-responsive mb-4">
              <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                  <tr><th>Day</th><th>Meal Type</th></tr>
                </thead>
                <tbody>
                  @foreach($days as $short => $full)
                    @php $existing = $existingMenus[$full]->meal_type ?? null; @endphp
                    <tr>
                      <td>{{ $full }}</td>
                      <td>
                        <select name="meal[{{ $short }}]" class="form-control meal-select" data-day="{{ $short }}" required>
                          <option value="">-- Select Meal --</option>
                          <option value="Veg" {{ $existing==='Veg' ? 'selected' : '' }}>Veg</option>
                          <option value="Egg" {{ $existing==='Egg' ? 'selected' : '' }}>Egg</option>
                          <option value="Chicken" {{ $existing==='Chicken' ? 'selected' : '' }}>Chicken</option>
                        </select>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-center">
              <button type="submit" class="btn btn-success px-5">Save Weekly Menu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Daily Prices Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card premium-card">
        <div class="card-body">
          <h3 class="card-title text-center mb-4">Daily Prices (based on effective pricing)</h3>

          {{-- Legend --}}
          <div class="mb-4 d-flex gap-4 justify-content-center">
            <div class="legend-item"><div></div> Veg</div>
            <div class="legend-item"><div></div> Egg</div>
            <div class="legend-item"><div></div> Chicken</div>
          </div>

          {{-- Prices Table --}}
          <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
              <thead class="table-light">
                <tr><th>Date</th><th>Day</th><th>Meal Type</th><th>Price</th></tr>
              </thead>
              <tbody>
                @foreach($datesInMonth as $date)
                  @php
                    $key = $date->format('Y-m-d');
                    $info = $dailyPrices[$key] ?? [];
                    $meal = $info['meal_type'] ?? null;
                    $price = $info['meal_price'] ?? null;
                    $rowClass = match($meal) {
                      'Veg'=>'meal-veg', 'Egg'=>'meal-egg', 'Chicken'=>'meal-chicken', default=>''
                    };
                  @endphp
                  <tr class="{{ $rowClass }}">
                    <td>{{ $date->format('d M Y') }}</td>
                    <td>{{ $info['day_name'] ?? $date->format('l') }}</td>
                    <td>{{ $meal ?? '-' }}</td>
                    <td>{{ $price!==null ? '₹'.number_format($price,2) : 'N/A' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

</div>

@push('styles')
<style>
  .premium-card {
    border: none;
    border-radius: .75rem;
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1);
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,.15);
  }
  .premium-card .card-body { padding: 2rem; }
  .premium-card .card-title { font-size:1.75rem; font-weight:600;}
  .table-hover tbody tr:hover { background-color:rgba(0,123,255,.05); }

  .legend-item { display:flex; align-items:center; gap:.5rem; }
  .legend-item > div {
    width:20px; height:20px; border:1px solid #ccc;
    background-color: #d4edda; /* adjust per type via nth-child or inline */
  }

  .meal-veg { background-color:#d4edda; color:#155724; }
  .meal-egg { background-color:#fff3cd; color:#856404; }
  .meal-chicken { background-color:#ffe6ee; color:#721c24; }
  .meal-veg:hover, .meal-egg:hover, .meal-chicken:hover { opacity:.9; }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', ()=>{
    document.querySelectorAll('.meal-select').forEach(sel=>{
      const update = () => {
        const c = sel.value==='Veg'?'#d4edda':sel.value==='Egg'?'#fff3cd':'#ffe6ee';
        sel.style.background=c;
      };
      update(); sel.addEventListener('change', update);
    });
  });
</script>
@endpush
@endsection
