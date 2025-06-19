<x-shop::layouts
    :title="__('Add Contribution')"
    :has-header="false"
    :has-footer="false"
>
    <div class="flex flex-col min-h-screen">

        {{-- Admin Header --}}
        @include('mumbos::layouts.partials.admin-header')

        <div class="flex flex-1">
            {{-- Admin Sidebar --}}
            @include('mumbos::layouts.partials.admin-sidebar')

            <main class="flex-1 p-6 bg-gray-50">
                <h1 class="text-2xl font-bold mb-6">{{ __('New Contribution') }}</h1>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('shop.shareholders.contributions.store') }}"
                      enctype="multipart/form-data"
                      class="bg-white shadow rounded-lg p-6 space-y-4">
                    @csrf

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium">Amount (KES)</label>
                        <input type="number" name="amount" step="0.01" required
                               value="{{ old('amount') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium">Payment Method</label>
                        <select name="payment_method" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2">
                            @foreach(['mpesa'=>'M-PESA','bank_transfer'=>'Bank Transfer','cash'=>'Cash'] as $value => $label)
                                <option value="{{ $value }}" {{ old('payment_method') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment Reference --}}
                    <div>
                        <label class="block text-sm font-medium">Transaction/Reference Code</label>
                        <input type="text" name="payment_reference" required
                               value="{{ old('payment_reference') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2"
                               placeholder="e.g. MPESA123ABC">
                    </div>

                    {{-- Upload Receipt --}}
                    <div>
                        <label class="block text-sm font-medium">Upload Payment Receipt (optional)</label>
                        <input type="file" name="payment_receipt"
                               class="mt-1 block w-full border rounded px-3 py-2">
                    </div>

                    {{-- Contributed At --}}
                    <div>
                        <label class="block text-sm font-medium">Date of Contribution</label>
                        <input type="date" name="contributed_at"
                               value="{{ old('contributed_at', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium">Any Notes (optional)</label>
                        <textarea name="note" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm px-3 py-2">{{ old('note') }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">
                            {{ __('Submit Contribution') }}
                        </button>
                    </div>
                </form>
            </main>
        </div>

        {{-- Admin Footer --}}
        @include('mumbos::layouts.partials.footer')
    </div>
</x-shop::layouts>
