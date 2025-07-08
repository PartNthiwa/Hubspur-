<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Incentive;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Http\Requests\IncentiveRequest; 

class IncentiveController extends Controller
{
    public function index()
    {
        $incentives = Incentive::with('shareholder')->orderBy('id')->paginate(15);
        return view('mumbos::admin.incentives.index', compact('incentives'));
    }

    public function create()
    {
        // Need a list of shareholders to attach incentives to:
        $shareholders = Shareholder::orderBy('full_name')->get();
        return view('mumbos::admin.incentives.create', compact('shareholders'));
    }

    public function store(IncentiveRequest $request)
    {
          $incentive = Incentive::create($request->validated());
        if ($request->filled('shareholder_id')) {
            $incentive->shareholders()->attach($request->input('shareholder_id'));
        }

        return redirect()
            ->route('admin.incentives.index')
            ->with('success', 'Incentive created successfully.');
    }

    public function edit(Incentive $incentive)
    {
        $shareholders = Shareholder::orderBy('full_name')->get();
        return view('mumbos::admin.incentives.edit', compact('incentive','shareholders'));
    }

    public function update(IncentiveRequest $request, Incentive $incentive)
    {
        $incentive->update($request->validated());

        return redirect()
            ->route('admin.incentives.index')
            ->with('success', 'Incentive updated successfully.');
    }

    public function destroy(Incentive $incentive)
    {
        $incentive->delete();

        return redirect()
            ->route('admin.incentives.index')
            ->with('success', 'Incentive deleted successfully.');
    }
}
