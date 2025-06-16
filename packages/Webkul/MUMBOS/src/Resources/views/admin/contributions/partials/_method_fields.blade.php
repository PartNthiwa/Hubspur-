@php
    // Pull existing values or defaults
    $method   = old('payment_method', $contribution->payment_method);
    $channel  = old('payment_channel', $contribution->payment_channel);
    $reference= old('payment_reference', $contribution->payment_reference);
@endphp

@if($method === 'bank_transfer')
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm text-gray-700 mb-1">Bank Name / Branch</label>
            <input type="text"
                   name="payment_channel"
                   value="{{ $channel }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">Transaction ID</label>
            <input type="text"
                   name="payment_reference"
                   value="{{ $reference }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
        @if($contribution->payment_receipt)
            <div class="col-span-2">
                <label class="block text-sm text-gray-700 mb-1">Current Receipt</label>
                <a href="{{ $contribution->receipt_url }}"
                   class="text-blue-600 hover:underline text-sm" target="_blank">
                    Download existing receipt
                </a>
            </div>
        @endif
        <div class="col-span-2">
            <label class="block text-sm text-gray-700 mb-1">Upload New Receipt</label>
            <input type="file" name="payment_receipt"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
    </div>

@elseif($method === 'mpesa')
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm text-gray-700 mb-1">Phone Number</label>
            <input type="text"
                   name="payment_reference"
                   value="{{ $reference }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm" />
        </div>
    </div>

@elseif($method === 'paypal')
    <div class="mt-4">
        <label class="block text-sm text-gray-700 mb-1">PayPal Order ID</label>
        <input type="text"
               name="payment_reference"
               value="{{ $reference }}"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm" />
    </div>

@else {{-- cash --}}
    <div class="mt-4">
        <p class="text-gray-600 text-sm">No additional details required for cash payments.</p>
    </div>
@endif
