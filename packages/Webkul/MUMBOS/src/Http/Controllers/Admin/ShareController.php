<?php
namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Webkul\MUMBOS\Models\Share;

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
            'available_units' => 'nullable|integer|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data['is_active'] = $data['status'] === 'active';
        unset($data['status']);

        if ($request->hasFile('icon_url')) {
            $path = $request->file('icon_url')->store('shares/icons', 'public');
            $data['icon_url'] = $path;
        }

        $data['total_value'] = $data['units'] * $data['price_per_unit'];

        Share::create($data);

        return redirect()->route('admin.shares.index')->with('success', 'Share created.');
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
            'class' => 'required|string',
            'units' => 'required|integer|min:1',
            'available_units' => 'nullable|integer|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data['is_active'] = $data['status'] === 'active';
        unset($data['status']);

        if ($request->hasFile('icon_url')) {
            if ($share->icon_url && Storage::disk('public')->exists($share->icon_url)) {
                Storage::disk('public')->delete($share->icon_url);
            }
            $path = $request->file('icon_url')->store('shares/icons', 'public');
            $data['icon_url'] = $path;
        }

        $data['total_value'] = $data['units'] * $data['price_per_unit'];

        $share->update($data);

        return redirect()->route('admin.shares.index')->with('success', 'Share updated successfully.');
    }

    public function destroy(Share $share)
    {
        if ($share->icon_url && Storage::disk('public')->exists($share->icon_url)) {
            Storage::disk('public')->delete($share->icon_url);
        }

        $share->delete();

        return redirect()->route('admin.shares.index')->with('success', 'Share deleted successfully.');
    }
}
