<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Edit Contribution #{{ $contribution->id }}</h2>
        <a href="{{ route('admin.contributions.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.contributions.update', $contribution) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6 bg-white p-6 rounded shadow"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6">
            {{-- Shareholder --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Shareholder <span class="text-red-500">*</span></label>
                <select name="shareholder_id" required
                        class="w-full border @error('shareholder_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach($shareholders as $sh)
                        <option value="{{ $sh->id }}" {{ old('shareholder_id', $contribution->shareholder_id) == $sh->id ? 'selected' : '' }}>
                            {{ $sh->customer->first_name }} {{ $sh->customer->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('shareholder_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Phase --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phase <span class="text-red-500">*</span></label>
                <select name="phase_id" required
                        class="w-full border @error('phase_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach($phases as $phase)
                        <option value="{{ $phase->id }}" {{ old('phase_id', $contribution->phase_id) == $phase->id ? 'selected' : '' }}>
                            {{ $phase->name }} (KES {{ number_format($phase->share_value, 2) }}/share)
                        </option>
                    @endforeach
                </select>
                @error('phase_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Contribution Type <span class="text-red-500">*</span></label>
                <select name="type" required
                        class="w-full border @error('type') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    <option value="">-- Select Type --</option>
                    @foreach(['membership', 'regular', 'capital', 'other'] as $type)
                        <option value="{{ $type }}" {{ old('type', $contribution->type) == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="amount" required
                       value="{{ old('amount', $contribution->amount) }}"
                       class="w-full border @error('amount') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
                @error('amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Currency --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
                <select name="currency"
                        class="w-full border @error('currency') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach(['KES', 'USD', 'EUR'] as $currency)
                        <option value="{{ $currency }}" {{ old('currency', $contribution->currency) == $currency ? 'selected' : '' }}>
                            {{ $currency }}
                        </option>
                    @endforeach
                </select>
                @error('currency') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                <input type="date" name="contributed_at" required
                       value="{{ old('contributed_at', optional($contribution->contributed_at)->format('Y-m-d')) }}"
                       class="w-full border @error('contributed_at') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2" />
                @error('contributed_at') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method"
                        class="w-full border @error('payment_method') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach(['cash', 'bank_transfer', 'mpesa', 'paypal'] as $method)
                        <option value="{{ $method }}" {{ old('payment_method', $contribution->payment_method) == $method ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </option>
                    @endforeach
                </select>
                @error('payment_method') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Payment Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Payment Status</label>
                <select name="payment_status"
                        class="w-full border @error('payment_status') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach(['pending', 'completed', 'failed'] as $status)
                        <option value="{{ $status }}" {{ old('payment_status', $contribution->payment_status) == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('payment_status') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Approval Status</label>
                <select name="status"
                        class="w-full border @error('status') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">
                    @foreach(['pending', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" {{ old('status', $contribution->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Note --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Note (optional)</label>
            <textarea name="note" rows="3"
                      class="w-full border @error('note') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2">{{ old('note', $contribution->note) }}</textarea>
            @error('note') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Payment Receipt --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Payment Receipt</label>
            @if($contribution->payment_receipt)
                <div class="mb-2">
                    <a href="{{ Storage::url($contribution->payment_receipt) }}"
                       target="_blank"
                       class="text-blue-600 hover:underline text-sm">
                        View current receipt
                    </a>
                </div>
            @endif
            <input type="file"
                   name="payment_receipt"
                   accept=".jpg,.jpeg,.png,.pdf"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            <p class="text-xs text-gray-500 mt-1">Upload a new file to replace the existing receipt.</p>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Contribution
            </button>
            <a href="{{ route('admin.contributions.index') }}"
               class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</x-admin::layouts>
