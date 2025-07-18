<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('created_at', 'desc')->paginate(10);
        return view('mumbos::admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('mumbos::admin.teams.create');
    }

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:2048',
    ]);

    // Generate slug
    $data['slug'] = Str::slug($data['name']);

    // Ensure uniqueness
    $originalSlug = $data['slug'];
    $counter = 1;
    while (Team::where('slug', $data['slug'])->exists()) {
        $data['slug'] = $originalSlug . '-' . $counter++;
    }

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
    }

    Team::create($data);

    return redirect()->route('admin.teams.index')->with('success', 'Team created.');
}

    public function edit(Team $team)
    {
        return view('mumbos::admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            // 'slug' => 'required|string|unique:teams,slug,' . $team->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

           $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('logo')) {
        if ($team->logo && Storage::disk('public')->exists($team->logo)) {
            Storage::disk('public')->delete($team->logo);
        }
        $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
    }

    $team->update($data);
    // Ensure slug uniqueness
    $originalSlug = $data['slug'];
    $counter = 1;
    while (Team::where('slug', $data['slug'])->where('id', '!=', $team->id)->exists()) {
        $data['slug'] = $originalSlug . '-' . $counter++;
        $counter++;
    }

        return redirect()->route('admin.teams.index')->with('success', 'Team updated.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Team deleted.');
    }
}
