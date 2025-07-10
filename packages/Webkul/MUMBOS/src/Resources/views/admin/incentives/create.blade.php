<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-8">
        <h2 class="text-lg font-semibold">New Incentive</h2>
       <a href="{{ route('admin.incentives.index') }}"
   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow transition">
    <x-heroicon-s-arrow-left class="w-5 h-5 text-white" />
    Back to Incentives
</a>

    </div>

    <div class="bg-white rounded-lg  p-6">
        <form method="POST" action="{{ route('admin.incentives.store') }}">
            @csrf

            {{-- Incentive Type --}}
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Incentive Type</label>
                <select name="type" id="type" class="mt-4 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach(['bonus', 'reward', 'recognition', 'gift', 'other'] as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4 ">
                <label for="description" class="block text-sm font-medium text-gray-700 ">Description</label>
                <textarea name="description" id="description" rows="10"  placeholder="Describe your incentive here for this Member/Shareholder" 
                          class="mt-2 block w-full border border-gray-300 rounded-md px-2 py-2">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Shareholders List --}}
            <div id="shareholders-container" class="mb-6">
                <h3 class="text-sm font-semibold mb-2">Assign to Shareholders</h3>
                {{-- Initial Row --}}
                <div class="flex items-center gap-4 mb-2" data-row>
                    <select name="shareholders[0][id]" class="w-1/2 border border-gray-300 rounded-md px-2 py-2"required>
                        <option value="">Select Shareholder</option>
                        @foreach($shareholders as $sh)
                            <option value="{{ $sh->id }}">
                                {{ $sh->full_name ?? $sh->customer->first_name . ' ' . $sh->customer->last_name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="shareholders[0][units]" min="1" placeholder="Units"
                           class="w-1/3 border border-gray-300 rounded-md px-2 py-2" required>

                    <button type="button" class="text-red-500 text-sm hover:text-red-700" onclick="removeRow(this)" style="display: none">
                        Remove
                    </button>
                </div>
            </div>

            {{-- Add Another Button --}}
            <button type="button" onclick="addRow()"
                    class="text-blue-600 text-sm hover:underline mb-4">
                + Add another Shareholder
            </button>

            {{-- Validation --}}
            @error('shareholders') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('shareholders.*.id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('shareholders.*.units') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

            {{-- Submit --}}
            <div class="mt-4">
                <button type="submit"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
                    Save Incentive
                </button>
            </div>
        </form>
    </div>

    {{-- Dynamic JS --}}
    <script>
        let rowIndex = 1;
        function addRow() {
            const container = document.getElementById('shareholders-container');
            const newRow = container.querySelector('[data-row]').cloneNode(true);

            // Update input names
            newRow.querySelectorAll('select, input').forEach((input) => {
                if (input.name.includes('[id]')) {
                    input.name = `shareholders[${rowIndex}][id]`;
                    input.value = '';
                }
                if (input.name.includes('[units]')) {
                    input.name = `shareholders[${rowIndex}][units]`;
                    input.value = '';
                }
            });

            // Show remove button
            newRow.querySelector('button').style.display = 'inline';

            container.appendChild(newRow);
            rowIndex++;
        }

        function removeRow(button) {
            button.closest('[data-row]').remove();
        }
    </script>


  <div class="mt-8 text-center text-sm text-gray-500 ">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>
</x-admin::layouts>
