<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuPricing;
use Carbon\Carbon;

class MenuPricingController extends Controller
{
    public function index()
    {
        // Get all pricings ordered by latest first
        $menuPricings = MenuPricing::orderBy('effective_from', 'desc')->get();

        // Get latest effective date or use today if none
        $latestEffectiveDate = MenuPricing::max('effective_from') ?? now()->toDateString();

        // Set min and max for input field
        $minDate = Carbon::parse($latestEffectiveDate)->format('Y-m-d');
        $maxDate = Carbon::parse($latestEffectiveDate)->addMonths(2)->format('Y-m-d');

        return view('menu_pricing.index', compact('menuPricings', 'minDate', 'maxDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'veg_price' => 'required|numeric|min:1',
            'egg_price' => 'required|numeric|min:1',
            'chicken_price' => 'required|numeric|min:1',
            'effective_from' => 'required|date',
        ]);

        $selectedDate = Carbon::parse($validated['effective_from']);

        // Get latest effective_from
        $latestEffectiveDate = MenuPricing::max('effective_from');

        if ($latestEffectiveDate) {
            $minAllowed = Carbon::parse($latestEffectiveDate);
            $maxAllowed = $minAllowed->copy()->addMonths(2);

            // Check range
            if ($selectedDate->lt($minAllowed) || $selectedDate->gt($maxAllowed)) {
                return redirect()->back()->withErrors([
                    'effective_from' => 'Date must be within 2 months from the last effective date: ' .
                        $minAllowed->format('d M Y') . ' to ' . $maxAllowed->format('d M Y')
                ])->withInput();
            }
        }

        // Auto-increment version
        $latestVersion = MenuPricing::max('version') ?? 0;
        $validated['version'] = $latestVersion + 1;

        MenuPricing::create($validated);

        return redirect()->route('menu-pricing.index')->with('success', 'Menu pricing saved successfully.');
    }
}
