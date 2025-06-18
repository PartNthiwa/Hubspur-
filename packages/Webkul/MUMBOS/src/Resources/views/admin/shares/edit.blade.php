<x-admin::layouts>
    <h1 class="text-xl font-semibold text-gray-800 mb-4">{{ isset($share) ? 'Edit Share' : 'Create Share' }}</h1>

    <form method="POST" action="{{ isset($share) ? route('admin.shares.update', $share) : route('admin.shares.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($share)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Share Class -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Share Class</label>
                <input type="text" name="class" value="{{ old('class', $share->class ?? '') }}"
                       class="w-full mt-1 p-2 border rounded" required>
            </div>

            <!-- Units -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Total Units</label>
                <input type="number" name="units" value="{{ old('units', $share->units ?? '') }}"
                       class="w-full mt-1 p-2 border rounded" required>
            </div>

            <!-- Available Units -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Available Units</label>
                <input type="number" name="available_units" value="{{ old('available_units', $share->available_units ?? '') }}"
                       class="w-full mt-1 p-2 border rounded">
            </div>

            <!-- Price per Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Price per Unit (Ksh)</label>
                <input type="number" step="0.01" name="price_per_unit"
                       value="{{ old('price_per_unit', $share->price_per_unit ?? '') }}"
                       class="w-full mt-1 p-2 border rounded" required>
            </div>

            <!-- Visibility -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" class="w-full mt-1 p-2 border rounded" required>
                    <option value="public" {{ old('visibility', $share->visibility ?? '') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ old('visibility', $share->visibility ?? '') == 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="w-full mt-1 p-2 border rounded" required>
                    <option value="active" {{ (old('status', ($share->is_active ?? false) ? 'active' : 'inactive')) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ (old('status', ($share->is_active ?? false) ? 'active' : 'inactive')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3"
                      class="w-full mt-1 p-2 border rounded">{{ old('description', $share->description ?? '') }}</textarea>
        </div>

        <!-- Icon Upload -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Share Icon (optional)</label>
            <input type="file" name="icon_url" class="w-full mt-1 p-2 border rounded">
            @if(isset($share) && $share->icon_url)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $share->icon_url) }}" alt="Share Icon" class="h-16 w-16 object-cover rounded">
                </div>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                {{ isset($share) ? 'Update Share' : 'Create Share' }}
            </button>
        </div>
    </form>
</x-admin::layouts>
