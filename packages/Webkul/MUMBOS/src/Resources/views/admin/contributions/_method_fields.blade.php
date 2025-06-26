@php
    $contribution = $contribution ?? new \Webkul\MUMBOS\Models\Contribution;
    $channel = old('payment_channel', $contribution->payment_channel);
    $reference = old('payment_reference', $contribution->payment_reference);
@endphp

{{-- Payment Method Select --}}
<div class="col-span-2">
    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
    <select v-model="method"
            id="payment_method"
            name="payment_method"
            class="w-full border-gray-300 rounded px-3 py-2 text-sm"
            required>
        @foreach(['cash','bank_transfer','mpesa','paypal'] as $m)
            <option value="{{ $m }}">
                {{ ucfirst(str_replace('_',' ', $m)) }}
            </option>
        @endforeach
    </select>
    @error('payment_method')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Dynamic Gateway Fields --}}
<div class="col-span-2 mt-4">
    {{-- Bank Transfer --}}
    <div v-if="method === 'bank_transfer'" class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm text-gray-700 mb-1">Bank Name / Branch</label>
            <input type="text" name="payment_channel" value="{{ $channel }}"
                   class="w-full border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="text-sm text-gray-700 mb-1">Transaction ID</label>
            <input type="text" name="payment_reference" value="{{ $reference }}"
                   class="w-full border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
    </div>

    {{-- M-Pesa --}}
    <div v-if="method === 'mpesa'" class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm text-gray-700 mb-1">Phone Number</label>
            <input type="text" name="payment_reference" value="{{ $reference }}"
                   class="w-full border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
    </div>

    {{-- PayPal --}}
    <div v-if="method === 'paypal'" class="mt-4">
        <label class="text-sm text-gray-700 mb-1">PayPal Order ID</label>
        <input type="text" name="payment_reference" value="{{ $reference }}"
               class="w-full border-gray-300 rounded px-3 py-2 text-sm" />
    </div>

    {{-- Cash --}}
    <div v-if="method === 'cash'" class="mt-4">
        <p class="text-gray-600 text-sm">No additional details required for cash payments.</p>
    </div>
</div>
