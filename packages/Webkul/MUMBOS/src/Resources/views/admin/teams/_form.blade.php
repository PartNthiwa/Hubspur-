
<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
    <input type="text" name="name" id="name"
           value="{{ old('name', $team->name ?? '') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
           required>
    @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Hidden Slug Field --}}
<input type="hidden" name="slug" value="{{ old('slug', $team->slug ?? '') }}">

  

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" id="description" rows="4"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
    >{{ old('description', $team->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="logo" class="block text-sm font-medium text-gray-700">Team Logo</label>
    <input type="file" name="logo" id="logo"
           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    @error('logo')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror

    @if (!empty($team?->logo))
        <div class="mt-2">
            <p class="text-sm text-gray-600">Current Logo:</p>
            <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="h-20 mt-1 rounded shadow">
        </div>
    @endif
</div>


<div class="flex gap-3 pt-4">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save
    </button>

    <a href="{{ route('admin.teams.index') }}"
       class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
        Cancel
    </a>
</div>
