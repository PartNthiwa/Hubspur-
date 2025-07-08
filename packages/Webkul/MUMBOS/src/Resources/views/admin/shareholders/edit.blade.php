<x-admin::layouts>
    <h2 class="text-lg font-semibold mb-4">Edit Shareholder</h2>

    <form action="{{ route('admin.shareholders.update', $shareholder) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Main shareholder fields --}}
        @includeIf('mumbos::admin.shareholders.partials._form', [
            'shareholder' => $shareholder,
            'customers' => $customers ?? null
        ])

        {{-- Membership Types --}}

        @if ($shareholder->membershipTypes->count())
    <div class="mb-4 text-sm text-gray-600">
        Already Joined: 
        @foreach($shareholder->membershipTypes as $existing)
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs mr-1">{{ $existing->type }}</span>
        @endforeach
    </div>
@endif

       @php
    $existingMembershipTypeIds = $shareholder->membershipTypes->pluck('id')->toArray();
        $availableMembershipTypes = $membershipTypes->whereNotIn('id', $existingMembershipTypeIds);
    @endphp

    <div class="mb-6">
        <label class="block text-sm font-medium text-black mb-2">Add New Membership(s)</label>

        @foreach($availableMembershipTypes as $type)
            <div class="flex items-center gap-4 mb-3">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="memberships[{{ $type->id }}][selected]" value="1" class="form-checkbox">
                    {{ $type->type }}
                </label>
                <input type="number"
                    name="memberships[{{ $type->id }}][amount_paid]"
                    placeholder="Amount paid (KES)"
                    class="border border-gray-300 rounded px-3 py-1 w-48 text-sm"
                    step="0.01"
                    min="0">
            </div>
        @endforeach
    </div>

      

        {{-- Phase --}}
        <div>
            <label for="phase_id" class="block text-sm font-medium text-gray-700">Select Phase</label>
            <select name="phase_id" id="phase_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">-- Select Phase --</option>
                @foreach($phases as $phase)
                    <option value="{{ $phase->id }}" {{ old('phase_id', $shareholder->phase_id) == $phase->id ? 'selected' : '' }}>
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
                <option value="{{ $incentive->id }}"
                    {{ in_array($incentive->id, old('incentives', $shareholder->incentives->pluck('id')->toArray())) ? 'selected' : '' }}>
                    {{ $incentive->type }}
                </option>
            @endforeach
        </select>

            @error('incentives')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Notes --}}
        <p class="text-sm text-gray-500">Update shareholder details and associations.</p>

        <div class="flex gap-3 pt-2">
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('admin.shareholders.index') }}" class="text-sm text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form>

    <hr class="my-6">
</x-admin::layouts>
