@php
    $isEdit = isset($contribution);
    $selectedStatus = old('status', $contribution->status ?? 'pending');
    $selectedPaymentStatus = old('payment_status', $contribution->payment_status ?? 'pending');
@endphp

<div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow-md">

    {{-- Shareholder --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Shareholder <span class="text-red-500">*</span>
        </label>
        <select name="shareholder_id" required
                class="w-full border @error('shareholder_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @foreach($shareholders as $sh)
                <option value="{{ $sh->id }}" 
                    {{ old('shareholder_id', $contribution->shareholder_id ?? '') == $sh->id ? 'selected' : '' }}>
                    {{ $sh->customer->first_name }} {{ $sh->customer->last_name }}
                </option>
            @endforeach
        </select>
        @error('shareholder_id')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Amount --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Amount <span class="text-red-500">*</span>
        </label>
        <input type="number" step="0.01" name="amount" required
               value="{{ old('amount', $contribution->amount ?? '') }}"
               class="w-full border @error('amount') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        @error('amount')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Currency --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
        <select name="currency"
                class="w-full border @error('currency') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @foreach(['KES','USD','EUR'] as $cur)
                <option value="{{ $cur }}"
                    {{ old('currency', $contribution->currency ?? 'KES') == $cur ? 'selected' : '' }}>
                    {{ $cur }}
                </option>
            @endforeach
        </select>
        @error('currency')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Payment Status --}}
    <div>
        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
        <select name="payment_status" id="payment_status" required
                class="mt-1 block w-full border @error('payment_status') border-red-900 @else border-gray-500 @enderror rounded-md shadow-sm">
            <option value="">-- Select --</option>
            <option value="pending"   {{ $selectedPaymentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ $selectedPaymentStatus === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed"    {{ $selectedPaymentStatus === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        @error('payment_status')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Approval Status --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Approval Status</label>
        <select name="status" id="status" required
                class="mt-1 block w-full border @error('status') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm">
            <option value="">-- Select --</option>
            <option value="pending"   {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved"  {{ $selectedStatus === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected"  {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        @error('status')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Payment Method --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
        <select name="payment_method" id="payment_method" required
                class="w-full border @error('payment_method') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @foreach(['cash','bank_transfer','mpesa','paypal'] as $method)
                <option value="{{ $method }}"
                    {{ old('payment_method', $contribution->payment_method ?? 'bank_transfer') == $method ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ',$method)) }}
                </option>
            @endforeach
        </select>
        @error('payment_method')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Dynamic Method Fields --}}
    <div class="col-span-2" id="method-fields">
        @if($isEdit)
            @include('mumbos::admin.contributions.partials._method_fields', ['contribution' => $contribution])
        @endif
    </div>

    {{-- Contributed At --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input
            type="date"
            name="contributed_at"
            required
            value="{{ old(
                'contributed_at',
                isset($contribution) && $contribution->contributed_at
                    ? $contribution->contributed_at->format('Y-m-d')
                    : ''
            ) }}"
            class="w-full border @error('contributed_at') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
        @error('contributed_at')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>
{{-- Note --}}
<div class="col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">Note (optional)</label>
    <textarea name="note"
              rows="3"
              class="w-full border @error('note') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >{{ old('note', $contribution->note ?? '') }}</textarea>
    @error('note')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>


</div>

<script>
    const methodFields = {
        cash: `<p class="text-gray-600">No extra details required for cash payments.</p>`,
        bank_transfer: `
            <div class="grid grid-cols-2 gap-4 mt-4">
              <input name="payment_channel" placeholder="Bank Name / Branch" class="border rounded px-3 py-2 w-full" />
              <input name="payment_reference" placeholder="Transaction ID" class="border rounded px-3 py-2 w-full" />
            </div>
        `,
        mpesa: `
            <div class="grid grid-cols-2 gap-4 mt-4">
              <input name="payment_channel" value="M-PESA" readonly class="border bg-gray-100 rounded px-3 py-2 w-full" />
              <input name="payment_reference" placeholder="Phone Number" class="border rounded px-3 py-2 w-full" />
            </div>
        `,
        paypal: `
            <div class="mt-4">
              <input name="payment_reference" placeholder="PayPal Order ID" class="border rounded px-3 py-2 w-full" />
            </div>
        `
    };

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('payment_method');
        const container = document.getElementById('method-fields');

        function renderFields() {
            container.innerHTML = methodFields[select.value] || '';
        }

        select.addEventListener('change', renderFields);
        renderFields();
    });
</script>
