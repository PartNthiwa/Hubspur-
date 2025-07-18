<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Webkul\MUMBOS\Models\Team;
use Webkul\MUMBOS\Models\LeadershipMember;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LeadershipController extends Controller
{
    public function index()
    {
        $leaders = LeadershipMember::with('team')->orderBy('priority')->paginate(10);
        return view('mumbos::admin.leaders.index', compact('leaders'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('mumbos::admin.leaders.create', compact('teams'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'team_id' => 'nullable|exists:teams,id',
        'name' => 'required|string',
        'position' => 'required|string',
        'slug' => 'nullable|string',
        'photo' => 'nullable|image',
        'bio' => 'nullable|string',
        'email' => 'nullable|email',
        'phone' => 'nullable|string',
        'linkedin_url' => 'nullable|url',
        'quote' => 'nullable|string',
        'qualifications' => 'nullable|string',
        'experience' => 'nullable|string',
        'social_links' => 'nullable|array',
        'status' => 'required|in:active,inactive,retired,suspended',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'priority' => 'nullable|integer',
    ]);

    // Generate unique slug
    $baseSlug = Str::slug($data['slug'] ?? $data['name']);
    $slug = $baseSlug;
    $count = 1;
    while (LeadershipMember::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $count++;
    }
    $data['slug'] = $slug;

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('leaders/photos', 'public');
    }

    LeadershipMember::create($data);

    return redirect()->route('admin.leaders.index')->with('success', 'Leader added successfully.');
}



    public function edit(LeadershipMember $leader)
    {
        $teams = Team::all();
        return view('mumbos::admin.leaders.edit', compact('leader', 'teams'));
    }

   
public function update(Request $request, LeadershipMember $leader)
{
    $data = $request->validate([
        'team_id' => 'nullable|exists:teams,id',
        'name' => 'required|string',
        'position' => 'required|string',
        'slug' => 'nullable|string',
        'photo' => 'nullable|image',
        'bio' => 'nullable|string',
        'email' => 'nullable|email',
        'phone' => 'nullable|string',
        'linkedin_url' => 'nullable|url',
        'quote' => 'nullable|string',
        'qualifications' => 'nullable|string',
        'experience' => 'nullable|string',
        'social_links' => 'nullable|array',
        'status' => 'required|in:active,inactive,retired,suspended',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'priority' => 'nullable|integer',
    ]);

    // Generate unique slug (skip current leader)
    $baseSlug = Str::slug($data['slug'] ?? $data['name']);
    $slug = $baseSlug;
    $count = 1;
    while (
        LeadershipMember::where('slug', $slug)
            ->where('id', '!=', $leader->id)
            ->exists()
    ) {
        $slug = $baseSlug . '-' . $count++;
    }
    $data['slug'] = $slug;

    if ($request->hasFile('photo')) {
        if ($leader->photo && Storage::disk('public')->exists($leader->photo)) {
            Storage::disk('public')->delete($leader->photo);
        }
        $data['photo'] = $request->file('photo')->store('leaders/photos', 'public');
    }

    $leader->update($data);

    return redirect()->route('admin.leaders.index')->with('success', 'Leader updated successfully.');
}

    public function destroy(LeadershipMember $leader)
    {
        if ($leader->photo && Storage::disk('public')->exists($leader->photo)) {
            Storage::disk('public')->delete($leader->photo);
        }

        $leader->delete();

        return redirect()->route('admin.leaders.index')->with('success', 'Leader deleted.');
    }

    public function show(LeadershipMember $leader)
    {
        $leader->load('team');
        return view('mumbos::admin.leaders.show', compact('leader'));
    }
}
