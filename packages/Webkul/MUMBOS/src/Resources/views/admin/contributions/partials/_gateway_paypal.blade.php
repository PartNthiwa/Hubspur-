@php
    $reference = old('payment_reference', $contribution->payment_reference ?? '');
@endphp

@php
    $reference = old('paypal_payment_reference', $contribution->payment_reference ?? '');
@endphp

<div class="bg-blue-50 rounded-xl p-6 mt-6 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- PayPal Order/Txn ID --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">PayPal Order / Txn ID</label>
            <input type="text"
                   name="paypal_payment_reference"
                   value="{{ $reference }}"
                   class="w-full border border-green-600 focus:ring-2 focus:ring-blue-500 focus:outline-none rounded-lg px-4 py-2 text-sm shadow-sm" />
            @error('paypal_payment_reference')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
