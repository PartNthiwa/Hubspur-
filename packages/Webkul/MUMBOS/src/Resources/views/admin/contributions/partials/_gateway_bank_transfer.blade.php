@php
    $channel   = old('payment_channel', $contribution->payment_channel  ?? '');
    $reference = old('payment_reference', $contribution->payment_reference ?? '');
@endphp

@php
    $channel   = old('payment_channel', $contribution->payment_channel  ?? '');
    $reference = old('bank_payment_reference', $contribution->payment_reference ?? '');
@endphp

<div class="bg-blue-50 rounded-xl p-6 mt-6 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Bank Name / Branch --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Name / Branch</label>
            <input type="text"
                   name="payment_channel"
                   value="{{ $channel }}"
                   class="w-full border border-green-600 focus:ring-2 focus:ring-green-500 focus:outline-none rounded-lg px-4 py-2 text-sm shadow-sm" />
            @error('payment_channel')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Transaction ID --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction ID</label>
            <input type="text"
                   name="bank_payment_reference"
                   value="{{ $reference }}"
                   class="w-full border border-green-600 focus:ring-2 focus:ring-blue-500 focus:outline-none rounded-lg px-4 py-2 text-sm shadow-sm" />
            @error('bank_payment_reference')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
           
        </div>
        <div>
             <h2><em>Our bank details</em></h2>
             <h1><strong>MUMBO KENYA DIASPORA INVESTMENTS LTD</strong></h1>
             <p>Bank Name : <em>Family Bank Account, Bungoma Branch</em><br>
                Account number: <em>0770 0004 9763</em></p>
        </div>
    </div>
</div>
