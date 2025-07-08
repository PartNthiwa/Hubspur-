<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Edit Membership Type</h2>
        <a href="{{ route('admin.membership-types.index') }}"
           class="inline-flex items-center bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm transition">
            ← Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.membership-types.update', $membershipType->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type Name</label>
                <input type="text" name="type" id="type"
                       value="{{ old('type', $membershipType->type) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="30"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $membershipType->description) }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="share_value" class="block text-sm font-medium text-gray-700">Membership Amount (KES)</label>
                <input type="number" name="share_value" id="share_value" step="0.01"
                       value="{{ old('share_value', $membershipType->share_value) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('share_value') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" id="visibility"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="public" {{ old('visibility', $membershipType->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ old('visibility', $membershipType->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                </select>
                @error('visibility') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

         <div class="mb-4">
    <label class="inline-flex items-center">
        {{-- Hidden fallback --}}
        <input type="hidden" name="is_active" value="0">

        {{-- Checkbox --}}
        <input type="checkbox" name="is_active" value="1" class="form-checkbox"
               {{ old('is_active', false) ? 'checked' : '' }}>
        <span class="ml-2 text-sm text-gray-700">Active</span>
    </label>
    @error('is_active') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

            <button type="submit"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
                Update Membership Type
            </button>
        </form>
    </div>
</x-admin::layouts>
