<x-admin::layouts>
    <h1 class="text-xl font-semibold text-gray-800 mb-4">{{ isset($share) ? 'Edit Share' : 'Create Share' }}</h1>

    <form method="POST"
          action="{{ isset($share) ? route('admin.shares.update', $share) : route('admin.shares.store') }}">
        @csrf
        @if(isset($share)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Share Class</label>
            <input type="text" name="class" value="{{ old('class', $share->class ?? '') }}"
                   class="w-full mt-1 p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Units</label>
            <input type="number" name="units" value="{{ old('units', $share->units ?? '') }}"
                   class="w-full mt-1 p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Price per Unit</label>
            <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', $share->price_per_unit ?? '') }}"
                   class="w-full mt-1 p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            {{ isset($share) ? 'Update' : 'Save' }}
        </button>
    </form>
</x-admin::layouts>
