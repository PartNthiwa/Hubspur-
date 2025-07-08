<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\MembershipType;

class MembershipTypeController extends Controller
{
    /**
     * Display all membership types.
     */
    public function index()
    {
        $membershipTypes = MembershipType::orderBy('created_at', 'desc')->get();

        return view('mumbos::admin.membership-types.index', compact('membershipTypes'));
    }

    /**
     * Show the form to create a new membership type.
     */
    public function create()
    {
        return view('mumbos::admin.membership-types.create');
    }

    /**
     * Store a new membership type.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'        => 'required|string|max:255|unique:membership_types,type',
            'share_value' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'visibility'  => 'required|in:public,private',
            'is_active'   => 'boolean',
        ]);

        MembershipType::create($data);

        return redirect()->route('admin.membership-types.index')->with('success', 'Membership type created successfully.');
    }

    /**
     * Show the form to edit a membership type.
     */
    public function edit(MembershipType $membershipType)
    {
        return view('mumbos::admin.membership-types.edit', compact('membershipType'));
    }

    /**
     * Update a membership type.
     */
    public function update(Request $request, MembershipType $membershipType)
    {
        $data = $request->validate([
            'type'        => 'required|string|max:255|unique:membership_types,type,' . $membershipType->id,
            'share_value' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'visibility'  => 'required|in:public,private',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $membershipType->update($data);

        return redirect()->route('admin.membership-types.index')->with('success', 'Membership type updated successfully.');
    }

    /**
     * Delete a membership type.
     */
    public function destroy(MembershipType $membershipType)
    {
        try {
            $membershipType->delete();

            return redirect()->route('admin.membership-types.index')->with('success', 'Membership type deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete membership type: ' . $e->getMessage());
        }
    }
}
