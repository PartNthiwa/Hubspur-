<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow-md">
      {{-- Customer --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>

        @if (isset($customers))
         
            <select name="customer_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', optional($shareholder)->customer_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        @else
          
            <input type="text" value="{{ $shareholder->customer->name ?? 'N/A' }}" 
                readonly 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed text-sm text-gray-700" />
        @endif
    </div>

        {{-- Shareholder Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Shareholder Number <span class="text-red-500">*</span>
            </label>
                <input type="text text-gray-700 txt-sm font-italic"
                name="shareholder_number"
                value="{{ old('shareholder_number', optional($shareholder)->shareholder_number) }}"
                placeholder="Sha Number is auto-generated" 
                readonly
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed focus:ring-0 focus:outline-none" />
        </div>

        {{-- ID Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
            <input type="text" name="id_number" value="{{ old('id_number', optional($shareholder)->id_number) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            @error('id_number')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                </div>

        {{-- KRA PIN --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">KRA PIN</label>
            <input type="text" name="kra_pin" value="{{ old('kra_pin', optional($shareholder)->kra_pin) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', optional($shareholder)->phone) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
       
            @error('phone')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                </div>
        {{-- Joined At --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Joined At</label>
          <input type="date" name="joined_at"
                value="{{ old('joined_at', optional($shareholder)->joined_at ? \Carbon\Carbon::parse($shareholder->joined_at)->format('Y-m-d') : '') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        @error('joined_at')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror

        </div>

        {{-- Is Active --}}
        <div class="flex items-center space-x-2 mt-6">
            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                   {{ old('is_active', optional($shareholder)->is_active) ? 'checked' : '' }}>
            <label class="text-sm font-medium text-gray-700">Is Active</label>
        </div>

        {{-- Is Board Member --}}
        <div class="flex items-center space-x-2 mt-6">
            <input type="checkbox" name="is_board_member" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                   {{ old('is_board_member', optional($shareholder)->is_board_member) ? 'checked' : '' }}>
            <label class="text-sm font-medium text-gray-700">Is Board Member</label>
        </div>

    </div>
</div>
