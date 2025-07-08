<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Add New Shareholder</h2>

        <a href="{{ route('admin.shareholders.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Go Back
        </a>
    </div>

    <form action="{{ route('admin.shareholders.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Main shareholder form --}}
        @include('mumbos::admin.shareholders.partials._form', [
            'shareholder' => $shareholder ?? null,
            'customers' => $customers
        ])
        
        {{-- Membership Types --}}
       <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Membership Contributions</label>

        @foreach($membershipTypes as $type)
            <div class="flex items-center gap-4 mb-2">
                <label class="flex items-center">
                    <input type="checkbox" name="memberships[{{ $type->id }}][selected]" value="1" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-800">{{ $type->type }}</span>
                </label>

                <input type="number"
                    name="memberships[{{ $type->id }}][amount_paid]"
                    placeholder="Amount (KES)"
                    class="w-40 border border-gray-300 rounded px-2 py-1 text-sm"
                    min="0">
            </div>
        @endforeach

        @error('memberships') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>




        {{-- Select Phase --}}
        <div>
            <label for="phase_id" class="block text-sm font-medium text-gray-700">Select Phase</label>
            <select name="phase_id" id="phase_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">-- Select Phase --</option>
                @foreach($phases as $phase)
                    <option value="{{ $phase->id }}" {{ old('phase_id') == $phase->id ? 'selected' : '' }}>
                        {{ $phase->name }} (KES {{ number_format($phase->share_value, 2) }}/share)
                    </option>
                @endforeach
            </select>
            @error('phase_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Incentives --}}
        <div>
            <label for="incentives" class="block text-sm font-medium text-black">Select Incentives (optional)</label>
            <select name="incentives[]" id="incentives" multiple
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($incentives as $incentive)
                    <option value="{{ $incentive->id }}" {{ collect(old('incentives'))->contains($incentive->id) ? 'selected' : '' }}>
                        {{ $incentive->type }}
                    </option>
                @endforeach
            </select>
            @error('incentives')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <p class="text-sm text-gray-500 mt-4 mb-2">Please fill in the details to add a new shareholder.</p>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save
            </button>

            <a href="{{ route('admin.shareholders.index') }}"
               class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</x-admin::layouts>
