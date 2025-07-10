<x-admin::layouts>

    {{-- Header --}}
    <div class="bg-white mt-8 mb-6 flex items-center justify-between ">
       
<h1 class="text-2xl font-semibold text-gray-900">
    Edit Incentive #{{ $incentive->id }}  
    
</h1>

        <a href="{{ route('admin.incentives.index') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            <x-heroicon-s-arrow-left class="w-5 h-5 text-white" />
            Back to List
        </a>
    </div>
<div class="flex gap-4 mb-8 px-2">
    <div class=" bg-green-100 border-l-4 border-red-600 text-yellow-800 p-4 rounded shadow-sm">
        <p class="text-sm">
            <strong>Note:</strong> <span style="color:red">You're making a global update.</span>
        </p>
    </div>
</div>
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg -mt-6 p-8">
        <form method="POST" action="{{ route('admin.incentives.update', $incentive) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">
                {{-- Type --}}
                <div>
                    <label for="type" class="block text-base font-semibold text-gray-800 mb-2">Incentive Name</label>
                    <select name="type" id="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300"
                            required>
                        @foreach(['bonus','reward','recognition','gift','other'] as $type)
                            <option value="{{ $type }}" {{ old('type', $incentive->type) === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-800 mb-2">Total Units for <span style="color:green">
    {{ \Illuminate\Support\Str::ucfirst(strtolower($incentive->type)) }} Incentive
</span>
 </label>
                    <input type="number" min="1"
                        placeholder="Auto-calculated or for reference"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                        value="{{ $incentive->shareholders->sum('pivot.units') }}" readonly>
                </div>
            </div>

            <div class="mt-8">
                <label class="block text-base font-semibold text-gray-800 mb-2">Update Shareholders & Incentive Units</label>

                <div id="shareholders-container" class="space-y-3">
                    @foreach ($incentive->shareholders as $index => $shareholder)
                        <div class="flex items-center gap-4" data-row>
                            <select name="shareholders[{{ $index }}][id]"
                                    class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg"
                                    required>
                                <option value="">Select Shareholder</option>
                                @foreach($shareholders as $sh)
                                    <option value="{{ $sh->id }}"
                                            {{ $sh->id == $shareholder->id ? 'selected' : '' }}>
                                        {{ $sh->full_name ?? ($sh->customer->first_name . ' ' . $sh->customer->last_name) }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="number"
                                   name="shareholders[{{ $index }}][units]"
                                   value="{{ $shareholder->pivot->units }}"
                                   min="1"
                                   placeholder="Units"
                                   class="w-1/3 px-3 py-2 border border-gray-300 rounded-lg"
                                   required>

                            <button type="button"
                                    class="text-red-500 text-sm hover:text-red-700"
                                    onclick="removeRow(this)"
                                    {{ $index === 0 ? 'style=display:none' : '' }}>
                                Remove
                            </button>
                        </div>
                    @endforeach
                </div>

           
                <button type="button"
                        onclick="addRow()"
                        class="text-blue-600 text-sm hover:underline mt-2">
                    + Add another Shareholder
                </button>

                @error('shareholders') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @error('shareholders.*.id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                @error('shareholders.*.units') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6">
                <label for="description" class="block text-base font-semibold text-gray-800 mb-2">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300"
                >{{ old('description', $incentive->description) }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="mt-6 text-right">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                    <x-heroicon-s-check class="w-5 h-5" />
                    Update Incentive
                </button>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

    {{-- JS --}}
    <script>
        let rowIndex = {{ $incentive->shareholders->count() }};
        function addRow() {
            const container = document.getElementById('shareholders-container');
            const firstRow = container.querySelector('[data-row]');
            const newRow = firstRow.cloneNode(true);

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

            newRow.querySelector('button').style.display = 'inline';
            container.appendChild(newRow);
            rowIndex++;
        }

        function removeRow(button) {
            button.closest('[data-row]').remove();
        }
    </script>

</x-admin::layouts>
