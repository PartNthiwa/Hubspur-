@php
    $selectedShareholderId = old('shareholder_id') 
        ?? ($contribution->shareholder_id ?? ($shareholders->first()?->id));

    $selectedShareholder = $shareholders->firstWhere('id', (int) $selectedShareholderId);
    $phone = old('phone', $selectedShareholder->customer->phone ?? '');
@endphp

<div x-show="method === 'mpesa'" x-data="{ mpesaPhone: '{{ $phone }}' }" class="bg-green-50 rounded-xl p-6 mt-6 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Phone Number --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
            <input type="text"
                   x-model="mpesaPhone"
                   name="phone"
                   placeholder="2547XXXXXXXX"
                   class="w-full border border-green-600 focus:ring-2 focus:ring-green-500 focus:outline-none rounded-lg px-4 py-2 text-sm shadow-sm" />

            @error('phone')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-4">
      
        <p class="text-sm text-gray-600">Click Save Contribution to initiate payment via M-Pesa STK Push.</p>
    </div>
</div>
