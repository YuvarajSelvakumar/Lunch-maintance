<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuPricing;

class MenuPricingController extends Controller
{
    public function index()
    {
        $menuPricings = MenuPricing::orderBy('month', 'desc')->get();
        return view('menu_pricing.index', compact('menuPricings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date',
            'veg_price' => 'required|numeric',
            'egg_price' => 'required|numeric',
            'chicken_price' => 'required|numeric',
            'effective_from' => 'required|date',
        ]);

        // Add versioning logic if needed
        $latestVersion = MenuPricing::where('month', $validated['month'])->max('version') ?? 0;
        $validated['version'] = $latestVersion + 1;

        MenuPricing::create($validated);

        return redirect()->route('menu-pricing.index')->with('success', 'Menu pricing saved successfully.');
    }
}
