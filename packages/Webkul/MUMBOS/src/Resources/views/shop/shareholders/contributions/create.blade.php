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

                {{-- Back button --}}
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center text-sm text-red-600 mt-8 hover:text-red-800 font-semibold mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>

                <h1 class="text-2xl font-bold mt-4 mb-2 text-gray-900">{{ __('Record / Make Contribution') }}</h1>

                {{-- Dismissible note --}}
                <div x-data="{ show: true }" x-show="show" class="mb-6 rounded border border-yellow-300 bg-yellow-50 p-6 text-sm text-yellow-800 relative">
                    <p>
                        This form is used to <strong>record a payment that has already been made for  <span style="color:red;">APPROVAL</span></strong> (e.g. via bank or cash),
                        or to <strong>initiate an M-PESA payment directly</strong>. Please ensure you provide accurate details.
                    </p>
                    <button @click="show = false" class="absolute top-2 right-2 text-yellow-700 hover:text-yellow-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Error messages --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form 
                    x-data="{ method: '{{ old('payment_method', 'mpesa') }}' }"
                    method="POST"
                    action="{{ route('shop.shareholders.contributions.store') }}"
                    enctype="multipart/form-data"
                    class="bg-white shadow rounded-lg p-6"
                >
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Contribution Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">Type of Contribution</label>
                            <select name="type" required
                                    class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900">
                                @foreach(['membership' => 'Membership', 'capital' => 'Capital', 'regular' => 'Regular', 'other' => 'Other'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('type') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Phase --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">Contribution Phase</label>
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
                        <div x-show="method === 'mpesa'" x-cloak class="md:col-span-2">
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
                        <div x-show="method === 'bank_transfer'" x-cloak class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-800">Bank Reference</label>
                            <input type="text" name="bank_payment_reference"
                                   value="{{ old('bank_payment_reference') }}"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900"
                                   placeholder="e.g. BANK123ABC">
                        </div>

                        {{-- Cash Note --}}
                        <div x-show="method === 'cash'" x-cloak class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-800">Cash Note</label>
                            <input type="text" name="cash_note"
                                   value="{{ old('cash_note') }}"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-gray-900"
                                   placeholder="e.g. Handed to Treasurer">
                        </div>

                        {{-- Upload Receipt --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-800">Upload Payment Receipt (optional)</label>
                            <input type="file" name="payment_receipt"
                                   class="mt-1 block w-full bg-white border border-gray-300 rounded px-3 py-2 text-gray-900">
                        </div>

                        {{-- Date --}}
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
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition font-semibold">
                            {{ __('Submit Contribution') }}
                        </button>
                    </div>
                </form>
            </main>
        </div>

        {{-- Footer --}}
        @include('mumbos::layouts.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-shop::layouts>
