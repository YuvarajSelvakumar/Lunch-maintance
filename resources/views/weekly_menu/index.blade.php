@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px;">
    <h2>Weekly Menu for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Month selector --}}
    <form method="GET" action="{{ route('weekly-menu.index') }}" style="margin-bottom: 20px;">
        <label for="month">Select Month: </label>
        <input type="month" id="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
    </form>

    {{-- Weekly Menu form --}}
    <form method="POST" action="{{ route('weekly-menu.store') }}">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}" />

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Meal Type</th>
                    <th>Meal Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                    @php
                        $existing = $existingMenus[$day] ?? null;
                        $selectedMeal = $existing ? $existing->meal_type : null;
                        $price = $existing ? $existing->meal_price : null;

                        // If no existing meal price, fallback to pricing table meal price
                        if (!$price && $pricing && $selectedMeal) {
                            $priceKey = strtolower($selectedMeal) . '_price';
                            $price = $pricing->$priceKey ?? null;
                        }
                    @endphp
                    <tr>
                        <td>{{ $day }}</td>
                        <td>
                            <select name="meal[{{ $day }}]" class="form-control" required>
                                <option value="Veg" {{ $selectedMeal == 'Veg' ? 'selected' : '' }}>Veg</option>
                                <option value="Egg" {{ $selectedMeal == 'Egg' ? 'selected' : '' }}>Egg</option>
                                <option value="Chicken" {{ $selectedMeal == 'Chicken' ? 'selected' : '' }}>Chicken</option>
                            </select>
                        </td>
                        <td>
                            {{ $price !== null ? number_format($price, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Save Weekly Menu</button>
    </form>

    {{-- Display saved Weekly Menu below --}}
    @if($existingMenus->count() > 0)
        <hr>
        <h3>Saved Weekly Menu for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Meal Type</th>
                    <th>Meal Price (₹)</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                    @php
                        $menu = $existingMenus[$day] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $day }}</td>
                        <td>{{ $menu ? $menu->meal_type : '-' }}</td>
                        <td>{{ $menu ? number_format($menu->meal_price, 2) : '-' }}</td>
                        <td>{{ $menu ? $menu->updated_at->format('d M Y, h:i A') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
