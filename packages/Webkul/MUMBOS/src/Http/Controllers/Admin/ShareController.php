<?php
namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Webkul\MUMBOS\Models\Share;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShareController extends Controller
{
    public function index()
    {
      
        $shares = Share::latest()->paginate(10);
        return view('mumbos::admin.shares.index', compact('shares'));
    }

    public function create()
    {
       
        return view('mumbos::admin.shares.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class' => 'required|string',
            'units' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        Share::create($data);

        return redirect()->route('admin.shares.index')->with('success', 'Share created.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \Webkul\MUMBOS\Models\Share  $share
     * @return \Illuminate\View\View
     */
    public function show(Share $share)
    {
        return view('mumbos::admin.shares.show', compact('share'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Webkul\MUMBOS\Models\Share  $share
     * @return \Illuminate\View\View
     */
    public function edit(Share $share)
    {
       
        return view('mumbos::admin.shares.edit', compact('share'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Webkul\MUMBOS\Models\Share  $share
     * @return \Illuminate\Http\RedirectResponse
     */
       public function update(Request $request, Share $share)
        {
            $data = $request->validate([
                'class' => 'required|string',
                'units' => 'required|integer|min:1',
                'price_per_unit' => 'required|numeric|min:0',
            ]);

            $share->update($data);


            return redirect()->route('admin.shares.index')->with('success', 'Share updated successfully.');
        }

    public function destroy(Share $share)
    {
        $share->delete();

        return redirect()->route('admin.shares.index')->with('success', 'Share deleted successfully.');
    }
}