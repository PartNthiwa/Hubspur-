<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Phase;
use Webkul\MUMBOS\Http\Requests\PhaseRequest;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class PhaseController extends Controller
{
    public function index()
    {
       $phases = Phase::withTrashed()
                   ->orderBy('id')
                   ->paginate(15);
        return view('mumbos::admin.phases.index', compact('phases'));
    }

public function restore($id): RedirectResponse
{
    $phase = Phase::withTrashed()->findOrFail($id);
    $phase->restore();

    return redirect()->route('admin.phases.index')->with('success', 'Phase restored successfully.');
}

public function forceDelete($id): RedirectResponse
{
    $phase = Phase::withTrashed()->findOrFail($id);
    $phase->forceDelete();

    return redirect()->route('admin.phases.index')->with('success', 'Phase permanently deleted.');
}

    public function create()
    {
        return view('mumbos::admin.phases.create');
    }

    public function store(PhaseRequest $request)
    {
        Phase::create($request->validated());

        return redirect()
            ->route('admin.phases.index')
            ->with('success', 'Phase created successfully.');
    }

    public function edit(Phase $phase)
    {
        return view('mumbos::admin.phases.edit', compact('phase'));
    }

    public function update(PhaseRequest $request, Phase $phase)
    {
        $phase->update($request->validated());

        return redirect()
            ->route('admin.phases.index')
            ->with('success', 'Phase updated successfully.');
    }

    public function destroy(Phase $phase)
    {
        $phase->delete();

        return redirect()
            ->route('admin.phases.index')
            ->with('success', 'Phase deleted successfully.');
    }
}
