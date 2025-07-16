@php
    $defaultMethod = old('payment_method') ?? ($contribution->payment_method ?? 'cash');
    $selectedPaymentMethod = old('payment_method', $contribution->payment_method ?? '');
@endphp

<div class="bg-white rounded-xl shadow-md p-6 space-y-6">

    {{-- Row 1: Shareholder + Phase + Type + Amount --}}
    <div class="grid grid-cols-2 md:grid-cols-2 gap-6">
        {{-- Shareholder --}}
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

        {{-- Phase --}}
        <div>
            <label for="phase_id" class="block text-sm font-semibold text-gray-700 mb-1">Phase <span class="text-red-500">*</span></label>
            <select name="phase_id" id="phase_id"
                    class="w-full border @error('phase_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" required>
                <option value="">Select Phase</option>
                @foreach($phases as $phase)
                    <option value="{{ $phase->id }}" {{ old('phase_id', $contribution->phase_id ?? '') == $phase->id ? 'selected' : '' }}>
                        {{ $phase->name }} (KES {{ number_format($phase->share_value,2) }}/share)
                    </option>
                @endforeach
            </select>
            @error('phase_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Type --}}
        <div>
            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Contribution Type <span class="text-red-500">*</span></label>
            <select name="type" id="type"
                    class="w-full border @error('type') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" required>
                <option value="">-- Select Type --</option>
                <option value="membership" {{ old('type', $contribution->type ?? '') === 'membership' ? 'selected' : '' }}>Membership</option>
                <option value="regular" {{ old('type', $contribution->type ?? '') === 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="capital" {{ old('type', $contribution->type ?? '') === 'capital' ? 'selected' : '' }}>Capital</option>
                <option value="other" {{ old('type', $contribution->type ?? '') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('type') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Amount --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="amount" required
                   value="{{ old('amount', $contribution->amount ?? '') }}"
                   class="w-full border @error('amount') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
            @error('amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Row 2: Currency + Date + Payment Status + Approval Status --}}
    <div class="grid grid-cols-2 md:grid-cols-2 gap-6">
        {{-- Currency --}}
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

        {{-- Date --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
            <input type="date" name="contributed_at" required
                   value="{{ old('contributed_at', isset($contribution) && $contribution->contributed_at ? $contribution->contributed_at->format('Y-m-d') : '') }}"
                   class="w-full border @error('contributed_at') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
            @error('contributed_at') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Payment Status --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Payment Status</label>
            <select name="payment_status"
                    class="w-full border @error('payment_status') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" required>
                <option value="pending" {{ old('payment_status', $contribution->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('payment_status', $contribution->payment_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ old('payment_status', $contribution->payment_status) === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            @error('payment_status') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Contribution Status</label>
            <select name="status"
                    class="w-full border @error('status') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" required>
                <option value="pending" {{ old('status', $contribution->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ old('status', $contribution->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('status', $contribution->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Row 3: Payment Method + Gateways --}}
    <div x-data="{ method: '{{ $defaultMethod }}' }"
         class="grid grid-cols-1 gap-6 mt-6 bg-gray-50 p-4 rounded-2xl shadow-sm">

        {{-- Payment Method --}}
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

        {{-- Conditional Payment Gateway Inputs --}}
        <div class="space-y-4">
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

    {{-- Row 4: Note --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Note (optional)</label>
        <textarea name="note" rows="3"
                  class="w-full border @error('note') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">{{ old('note', $contribution->note ?? '') }}</textarea>
        @error('note') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>
