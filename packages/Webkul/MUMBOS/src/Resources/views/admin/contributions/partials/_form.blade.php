@php
    $defaultMethod = old('payment_method') ?? ($contribution->payment_method ?? 'cash');
     $selectedPaymentMethod = old('payment_method', $contribution->payment_method ?? '');
@endphp

<div class="bg-white rounded-xl shadow-md p-6 space-y-6">

    {{-- Row 1: Shareholder + Amount --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Shareholder <span class="text-red-500">*</span></label>
            <select name="shareholder_id" required
                    class="w-full border @error('shareholder_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                @foreach($shareholders as $sh)
                    <option value="{{ $sh->id }}" {{ old('shareholder_id', $contribution->shareholder_id ?? '') == $sh->id ? 'selected' : '' }}>
                        {{ $sh->customer->first_name }} {{ $sh->customer->last_name }}
                    </option>
                @endforeach
            </select>
            @error('shareholder_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="amount" required
                   value="{{ old('amount', $contribution->amount ?? '') }}"
                   class="w-full border @error('amount') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
            @error('amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Row 2: Currency + Payment Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
            <select name="currency"
                    class="w-full border @error('currency') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                @foreach(['KES','USD','EUR'] as $cur)
                    <option value="{{ $cur }}" {{ old('currency', $contribution->currency ?? 'KES') == $cur ? 'selected' : '' }}>
                        {{ $cur }}
                    </option>
                @endforeach
            </select>
            @error('currency') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
<div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
            <input type="date" name="contributed_at" required
                   value="{{ old('contributed_at', isset($contribution) && $contribution->contributed_at ? $contribution->contributed_at->format('Y-m-d') : '') }}"
                   class="w-full border @error('contributed_at') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
            @error('contributed_at') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>


    {{-- Row 4: Payment Method --}}
    <div x-data="{ method: '{{ $defaultMethod }}' }"
         x-init="$watch('method', value => console.log('Selected payment method:', value))"
         class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 bg-gray-50 p-4 rounded-4xl shadow-sm mb-6">

        {{-- Payment Method Selection --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Payment Method</label>
            <select name="payment_method" x-model="method"
                    class="w-full border @error('payment_method') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                <option value="" disabled>Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="mpesa">M-Pesa</option>
                <option value="paypal">PayPal</option>
            </select>
            @error('payment_method') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Payment Method Details --}}
        <div class="mt-4 space-y-4">
            <div x-show="method === 'cash'" class="p-4 bg-gray-50 border rounded">
                @include('mumbos::admin.contributions.partials._gateway_cash')
            </div>

            <div x-show="method === 'bank_transfer'" class="p-4 bg-gray-50 border rounded">
                @include('mumbos::admin.contributions.partials._gateway_bank_transfer')
            </div>

            <div x-show="method === 'mpesa'" class="p-4 bg-gray-50 border rounded">
                @include('mumbos::admin.contributions.partials._gateway_mpesa')
            </div>

            <div x-show="method === 'paypal'" class="p-4 bg-gray-50 border rounded">
                @include('mumbos::admin.contributions.partials._gateway_paypal')
            </div>
        </div>
    </div>

    {{-- Row 5: Note --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Note (optional)</label>
        <textarea name="note" rows="3"
                  class="w-full border @error('note') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">{{ old('note', $contribution->note ?? '') }}</textarea>
        @error('note') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

</div>
