<x-shop::layouts
    :title="__('Add Contribution')"
    :has-header="false"
    :has-footer="false"
>
    <style>[x-cloak] { display: none !important; }</style>

    <div class="flex flex-col min-h-screen">

        {{-- Admin Header --}}
        @include('mumbos::layouts.partials.admin-header')

        <div class="flex flex-1">
            {{-- Admin Sidebar --}}
            @include('mumbos::layouts.partials.admin-sidebar')

            <main class="flex-1 p-6 bg-gray-50">
                <h1 class="text-2xl font-bold mb-6 text-gray-900">{{ __('Make Contribution') }}</h1>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form 
                    x-data="{ method: '{{ old('payment_method', 'mpesa') }}' }"
                    method="POST"
                    action="{{ route('shop.shareholders.contributions.store') }}"
                    enctype="multipart/form-data"
                    class="bg-white shadow rounded-lg p-6 space-y-5"
                >
                    @csrf

                    <div>
                    <label class="block text-sm font-semibold text-gray-800">Contribution Type</label>
                    <select name="type" required
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                        @foreach(['membership' => 'Membership', 'capital' => 'Capital', 'regular' => 'Regular', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('type') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800">Phase</label>
                    <select name="phase_id" required
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                        @foreach(\Webkul\MUMBOS\Models\Phase::orderBy('id')->get() as $phase)
                            <option value="{{ $phase->id }}" @selected(old('phase_id') == $phase->id)>
                                {{ $phase->title ?? 'Phase ' . $phase->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Amount (KES)</label>
                        <input type="number" name="amount" step="0.01" required
                               value="{{ old('amount') }}"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Payment Method</label>
                        <select name="payment_method" x-model="method"
                                class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                            @foreach(['mpesa'=>'M-PESA','bank_transfer'=>'Bank Transfer','cash'=>'Cash'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- M-Pesa Phone --}}
                    <div x-show="method === 'mpesa'" x-cloak>
                        <label class="block text-sm font-semibold text-gray-800">Phone Number</label>
                        @php
                            $shareholderPhone = old('phone', Auth::user()->shareholder->phone ?? '');
                        @endphp
                        <input type="text" name="phone"
                            placeholder="e.g. 254712345678"
                            value="{{ $shareholderPhone }}"
                            class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                    </div>

                    {{-- Bank Reference --}}
                    <div x-show="method === 'bank_transfer'" x-cloak>
                        <label class="block text-sm font-semibold text-gray-800">Bank Reference</label>
                        <input type="text" name="bank_payment_reference"
                               value="{{ old('bank_payment_reference') }}"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900"
                               placeholder="e.g. BANK123ABC">
                    </div>

                    {{-- Cash Note --}}
                    <div x-show="method === 'cash'" x-cloak>
                        <label class="block text-sm font-semibold text-gray-800">Cash Note</label>
                        <input type="text" name="cash_note"
                               value="{{ old('cash_note') }}"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900"
                               placeholder="e.g. Handed to Treasurer">
                    </div>

                    {{-- Upload Receipt --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Upload Payment Receipt (optional)</label>
                        <input type="file" name="payment_receipt"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded px-3 py-2 text-gray-900">
                    </div>

                    {{-- Date of Contribution --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Date of Contribution</label>
                        <input type="date" name="contributed_at"
                               value="{{ old('contributed_at', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Any Notes (optional)</label>
                        <textarea name="note" rows="3"
                                  class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">{{ old('note') }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition font-semibold">
                            {{ __('Submit Contribution') }}
                        </button>
                    </div>
                </form>
            </main>
        </div>

        {{-- Admin Footer --}}
        @include('mumbos::layouts.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-shop::layouts>
