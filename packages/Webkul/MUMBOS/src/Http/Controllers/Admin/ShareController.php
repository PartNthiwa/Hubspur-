<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Share;
use Webkul\MUMBOS\Models\Shareholder;

class ShareController extends Controller
{
    public function index()
    {
        $shares = Share::latest()->paginate(10);

        return view('mumbos::admin.shares.index', compact('shares'));
    }



 public function updateAllocation(Request $request, Share $share, Shareholder $shareholder)
{
    $data = $request->validate([
        'units' => 'required|integer|min:0',
    ]);

    $current = $share->shareholders()->wherePivot('shareholder_id', $shareholder->id)->first();
    $currentUnits = $current?->pivot->units ?? 0;

    $allocatedUnits = $share->shareholders()->sum('shareholder_share.units');
    $availableUnits = $share->units - ($allocatedUnits - $currentUnits);

    if ($data['units'] > $availableUnits) {
        return back()->withErrors(['units' => "Only $availableUnits unit(s) available for allocation."]);
    }

    if ($data['units'] > 0) {
        $share->shareholders()->syncWithoutDetaching([
            $shareholder->id => ['units' => $data['units']]
        ]);
    } else {
        $share->shareholders()->detach($shareholder->id);
    }

    return back()->with('success', 'Allocation updated.');
}



     // show the allocation form
    public function allocateForm(Share $share)
    {
        // Fetch all shareholders to allocate shares to
        $shareholders = Shareholder::orderBy('full_name')->get();

        return view('mumbos::admin.shares.allocate', compact('share', 'shareholders'));
    }
 

  public function allocate(Request $request, Share $share)
{
    $data = $request->validate([
        'shareholder_id' => 'required|exists:shareholders,id',
        'units'          => 'required|integer|min:1',
    ]);


    $allocatedUnits = $share->shareholders()->sum('shareholder_share.units');
    $availableUnits = $share->units - $allocatedUnits;

    if ($availableUnits < $data['units']) {
        return back()->withErrors(['units' => "Only $availableUnits unit(s) available for allocation."]);
    }

    $existing = $share->shareholders()
                      ->wherePivot('shareholder_id', $data['shareholder_id'])
                      ->first();

    if ($existing) {
        $newUnits = $existing->pivot->units + $data['units'];

        // Check again if total after adding exceeds max
        if ($allocatedUnits - $existing->pivot->units + $newUnits > $share->units) {
            return back()->withErrors(['units' => "Insufficient units available for this allocation."]);
        }

        $share->shareholders()->updateExistingPivot($data['shareholder_id'], ['units' => $newUnits]);
    } else {
        $share->shareholders()->attach($data['shareholder_id'], ['units' => $data['units']]);
    }

    return redirect()
        ->route('admin.shares.show', $share)
        ->with('success', "Assigned {$data['units']} unit(s) to shareholder.");
}

    
    public function create()
    {
        return view('mumbos::admin.shares.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class'       => 'required|string|max:255',
            'origin'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'units'       => 'required|integer|min:1',
        ]);

        Share::create($data);

        return redirect()
            ->route('admin.shares.index')
            ->with('success', 'Share class created successfully.');
    }

    public function show(Share $share)
    {
        return view('mumbos::admin.shares.show', compact('share'));
    }

    public function edit(Share $share)
    {
        return view('mumbos::admin.shares.edit', compact('share'));
    }

    public function update(Request $request, Share $share)
    {
        $data = $request->validate([
            'class'       => 'required|string|max:255',
            'origin'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'units'       => 'required|integer|min:1',
        ]);

        $share->update($data);

        return redirect()
            ->route('admin.shares.index')
            ->with('success', 'Share class updated successfully.');
    }

    public function destroy(Share $share)
    {
        $share->delete();

        return redirect()
            ->route('admin.shares.index')
            ->with('success', 'Share class deleted.');
    }
}
