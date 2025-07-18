<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', $leader->name ?? '') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
           required>
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<input type="hidden" name="slug" value="{{ old('slug', $leader->slug ?? '') }}">


<div>
    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
    <input type="text" name="position" id="position"
           value="{{ old('position', $leader->position ?? '') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="team_id" class="block text-sm font-medium text-gray-700">Team</label>
    <select name="team_id" id="team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Select Team --</option>
        @foreach($teams as $team)
            <option value="{{ $team->id }}" {{ old('team_id', $leader->team_id ?? '') == $team->id ? 'selected' : '' }}>
                {{ $team->name }}
            </option>
        @endforeach
    </select>
    @error('team_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="photo" class="block text-sm font-medium text-gray-700">Photo (optional)</label>
    <input type="file" name="photo" id="photo"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    @if (!empty($leader?->photo_url))
        <p class="mt-2 text-sm text-gray-600">Current: <a href="{{ $leader->photo_url }}" class="text-blue-600" target="_blank">View</a></p>
    @endif
    @error('photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="bio" class="block text-sm font-medium text-gray-700">Short Bio</label>
    <textarea name="bio" id="bio" rows="4"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">{{ old('bio', $leader->bio ?? '') }}</textarea>
    @error('bio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="active" {{ old('status', $leader->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $leader->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="retired" {{ old('status', $leader->status ?? '') === 'retired' ? 'selected' : '' }}>Retired</option>
        <option value="suspended" {{ old('status', $leader->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>


<div class="flex gap-3 pt-2">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save
    </button>

    <a href="{{ route('admin.leaders.index') }}"
       class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
        Cancel
    </a>
</div>
