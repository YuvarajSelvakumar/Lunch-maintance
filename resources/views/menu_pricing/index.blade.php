@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Menu Pricing</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Pricing Form --}}
    <form method="POST" action="{{ route('menu-pricing.store') }}" class="mb-5">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Month</label>
                <input type="month" name="month" class="form-control" required value="{{ old('month') }}">
                @error('month')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">Veg Price</label>
                <input type="number" step="0.01" name="veg_price" class="form-control" required value="{{ old('veg_price') }}">
                @error('veg_price')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">Egg Price</label>
                <input type="number" step="0.01" name="egg_price" class="form-control" required value="{{ old('egg_price') }}">
                @error('egg_price')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">Chicken Price</label>
                <input type="number" step="0.01" name="chicken_price" class="form-control" required value="{{ old('chicken_price') }}">
                @error('chicken_price')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Effective From</label>
                <input type="date" name="effective_from" class="form-control" required value="{{ old('effective_from') }}">
                @error('effective_from')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Pricing</button>
    </form>

    {{-- Existing Pricings --}}
    <h3>Existing Menu Pricings</h3>
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Month</th>
                <th>Veg Price</th>
                <th>Egg Price</th>
                <th>Chicken Price</th>
                <th>Version</th>
                <th>Effective From</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menuPricings as $pricing)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pricing->month)->format('F Y') }}</td>
                    <td>{{ number_format($pricing->veg_price, 2) }}</td>
                    <td>{{ number_format($pricing->egg_price, 2) }}</td>
                    <td>{{ number_format($pricing->chicken_price, 2) }}</td>
                    <td>{{ $pricing->version }}</td>
                    <td>{{ \Carbon\Carbon::parse($pricing->effective_from)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No menu pricing found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
