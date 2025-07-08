<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">New Phase</h2>
        <a href="{{ route('admin.phases.index') }}"
           class="inline-flex items-center bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm transition">
            Back to Phases
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.phases.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('name') }}" required>
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="share_value" class="block text-sm font-medium text-gray-700">
                    Value per Share (KES)
                </label>
                <input type="number" name="share_value" id="share_value" step="0.01"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('share_value') }}" required>
                @error('share_value')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
                Save Phase
            </button>
        </form>
    </div>
</x-admin::layouts>
