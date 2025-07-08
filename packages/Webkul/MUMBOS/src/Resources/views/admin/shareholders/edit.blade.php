<x-admin::layouts>
    <h2 class="text-lg font-semibold mb-4">Edit Shareholder</h2>

    <form action="{{ route('admin.shareholders.update', $shareholder) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Main shareholder fields --}}
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow-md">

                {{-- First Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name"
                           value="{{ old('first_name', optional($shareholder->customer)->first_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- Last Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name"
                           value="{{ old('last_name', optional($shareholder->customer)->last_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', optional($shareholder)->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', optional($shareholder)->phone) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    @error('phone')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ID Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                    <input type="text" name="id_number"
                           value="{{ old('id_number', optional($shareholder)->id_number) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- KRA PIN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KRA PIN</label>
                    <input type="text" name="kra_pin"
                           value="{{ old('kra_pin', optional($shareholder)->kra_pin) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- Joined At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Joined At</label>
                    <input type="date" name="joined_at"
                           value="{{ old('joined_at', optional($shareholder->joined_at) ? \Carbon\Carbon::parse($shareholder->joined_at)->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                {{-- Is Active --}}
                <div class="flex items-center space-x-2 mt-6">
                    <input type="checkbox" name="is_active" value="1"
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                           {{ old('is_active', optional($shareholder)->is_active) ? 'checked' : '' }}>
                    <label class="text-sm font-medium text-gray-700">Is Active</label>
                </div>

                {{-- Is Board Member --}}
                <div class="flex items-center space-x-2 mt-6">
                    <input type="checkbox" name="is_board_member" value="1"
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                           {{ old('is_board_member', optional($shareholder)->is_board_member) ? 'checked' : '' }}>
                    <label class="text-sm font-medium text-gray-700">Is Board Member</label>
                </div>
            </div>
        </div>

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
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
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
        <p class="text-sm text-gray-500 mt-4">Update shareholder details and associations.</p>

        {{-- Submit --}}
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('admin.shareholders.index') }}" class="text-sm text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form> <br><br>

<h2 class="text-lg font-bold text-gray-800">Shareholder Details</h2>
<div class="w-full h-2 my-6 rounded-full bg-gradient-to-r from-green-600 via-blue-500 to-purple-600 shadow-md"></div>
<div style="height: 4px; background: linear-gradient(to right, green, blue, red);"></div>

</x-admin::layouts>
