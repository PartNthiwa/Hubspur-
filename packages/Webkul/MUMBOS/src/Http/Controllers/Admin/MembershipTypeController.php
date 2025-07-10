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
  
public function index(Request $request)
{
    $query = MembershipType::query();

    // 1. Status filter
    if ($request->filled('status')) {
        $query->where('is_active', $request->status);
    }

    // 2. Search filter (on type OR description)
    if ($request->filled('search')) {
        $term = '%' . $request->search . '%';
        $query->where(function($q) use ($term) {
            $q->where('type', 'like', $term)
              ->orWhere('description', 'like', $term);
        });
    }

    // 3. Ordering & pagination
    $membershipTypes = $query
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString(); // preserves ?status=&search= in pagination links

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

        $membershipType->is_active = $request->boolean('is_active');

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
