<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">New Incentive</h2>
        <a href="{{ route('admin.incentives.index') }}"
           class="inline-flex items-center bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm transition">
            Back to Incentives
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.incentives.store') }}">
            @csrf

            <div class="mb-4">
                <label for="shareholder_id" class="block text-sm font-medium text-gray-700">
                    Shareholder
                </label>
                <select name="shareholder_id" id="shareholder_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select Shareholder</option>
                    @foreach($shareholders as $sh)
                        <option value="{{ $sh->id }}" {{ old('shareholder_id')==$sh->id?'selected':'' }}>
                            {{ $sh->full_name ?? $sh->customer->first_name.' '.$sh->customer->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('shareholder_id')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach(['first','second','third','other'] as $type)
                        <option value="{{ $type }}" {{ old('type')==$type?'selected':'' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="units" class="block text-sm font-medium text-gray-700">Units</label>
                <input type="number" name="units" id="units" min="0"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                       value="{{ old('units') }}" required>
                @error('units')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
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
                Save Incentive
            </button>
        </form>
    </div>
</x-admin::layouts>
