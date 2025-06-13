<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContributionController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
     public function index()
    {
        $contributions = Contribution::with('shareholder.customer')->paginate(20);
        return view('mumbos::admin.contributions.index', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
   public function create()
    {
        $shareholders = Shareholder::where('is_active', true)->get();
        return view('mumbos::admin.contributions.create', compact('shareholders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'shareholder_id'  => 'required|exists:mvshareholders,id',
            'amount'          => 'required|numeric',
            'type'            => 'required|string',
            'contributed_at'  => 'required|date',
            'reference'       => 'nullable|string',
            'note'            => 'nullable|string',
        ]);

        $data['recorded_by'] = Auth::guard('admin')->id();
        Contribution::create($data);

        return redirect()->route('admin.contributions.index')
                         ->with('success', 'Contribution recorded.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        return view('mumbos::admin.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        
    }
}
