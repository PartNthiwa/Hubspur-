<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Incentive;
use Webkul\MUMBOS\Models\Shareholder;

class IncentiveController extends Controller
{
    public function index()
    {
        $incentives = Incentive::with('shareholders')->orderBy('id')->paginate(15);
        return view('mumbos::admin.incentives.index', compact('incentives'));
    }

    public function create()
    {
           $shareholders = Shareholder::with('customer')->orderBy('full_name')->get();

        return view('mumbos::admin.incentives.create', compact('shareholders'));
    }

public function updateUnits(Request $request, $incentive_id, $shareholder_number)
{
    \Log::info('Raw route inputs', [
        'incentive_id' => $incentive_id,
        'shareholder_number' => $shareholder_number,
        'units' => $request->units,
    ]);

    $request->validate([
        'units' => 'required|numeric|min:0',
    ]);

    $incentive = Incentive::findOrFail($incentive_id);
    $shareholder = Shareholder::where('shareholder_number', $shareholder_number)->firstOrFail();

    \Log::info('Resolved models', [
        'incentive_id' => $incentive->id,
        'shareholder_id' => $shareholder->id,
        'shareholder_number' => $shareholder->shareholder_number,
    ]);

    $incentive->shareholders()->updateExistingPivot($shareholder->id, [
        'units' => $request->units,
    ]);

   return redirect()->back()->with('success', 'Incentive units updated successfully.');

}


    public function store(Request $request)
    {


        $data = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'shareholders' => 'required|array',
            'shareholders.*.id' => 'required|exists:shareholders,id',
            'shareholders.*.units' => 'required|integer|min:1',
        ]);

        $incentive = Incentive::create([
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
        ]);

        // Sync shareholders with units
        $pivotData = collect($data['shareholders'])->mapWithKeys(function ($item) {
            return [$item['id'] => ['units' => $item['units']]];
        });

        $incentive->shareholders()->sync($pivotData);

        return redirect()->route('admin.incentives.index')
                         ->with('success', 'Incentive created successfully.');
    }

    public function edit(Incentive $incentive)
    {
        $incentive->load('shareholders'); // Load pivot data
        $shareholders = Shareholder::orderBy('full_name')->get();

        return view('mumbos::admin.incentives.edit', compact('incentive', 'shareholders'));
    }

    public function update(Request $request, Incentive $incentive)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'shareholders' => 'required|array',
            'shareholders.*.id' => 'required|exists:shareholders,id',
            'shareholders.*.units' => 'required|integer|min:1',
        ]);

        $incentive->update([
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
        ]);

        // Sync updated pivot data
        $pivotData = collect($data['shareholders'])->mapWithKeys(function ($item) {
            return [$item['id'] => ['units' => $item['units']]];
        });

        $incentive->shareholders()->sync($pivotData);

        return redirect()->route('admin.incentives.index')
                         ->with('success', 'Incentive updated successfully.');
    }

    public function destroy(Incentive $incentive)
    {
        $incentive->delete();

        return redirect()->route('admin.incentives.index')
                         ->with('success', 'Incentive deleted successfully.');
    }
}
