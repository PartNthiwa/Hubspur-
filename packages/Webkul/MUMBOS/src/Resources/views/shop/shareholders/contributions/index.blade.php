<x-shop::layouts
    :title="__('My Contributions')"
    :has-header="false"
    :has-footer="false"
>
    <div class="flex flex-col min-h-screen">
        {{-- Header --}}
        @include('mumbos::layouts.partials.admin-header')

        <div class="flex flex-1">
            {{-- Sidebar --}}
            @include('mumbos::layouts.partials.admin-sidebar')

            <main class="flex-1 p-6 bg-gray-50">
                <h1 class="text-2xl font-bold mb-6">{{ __('My Contributions') }}</h1>

                <a href="{{ route('shop.shareholders.contributions.create') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
                   + {{ __('New Contribution') }}
                </a>

                @if ($contributions->isEmpty())
                    <p class="text-gray-600">{{ __('You have not made any contributions yet.') }}</p>
                @else
                    <div class="overflow-x-auto bg-white shadow rounded-lg">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left">{{ __('Date') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Amount') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Method') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Reference') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Receipt') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Approved By') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-gray-700">
                                @foreach ($contributions as $c)
                                    <tr>
                                        <td class="px-4 py-3">{{ $c->contributed_at->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3">{{ $c->currency }} {{ number_format($c->amount, 2) }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($c->payment_method) }}</td>
                                        <td class="px-4 py-3">{{ $c->payment_reference ?? '—' }}</td>

                                        {{-- Receipt --}}
                                     
                                    <td class="px-4 py-3">
                                        @if ($c->payment_receipt)
                                            <button onclick="openModal('{{ Storage::url($c->payment_receipt) }}')"
                                                    class="text-blue-600 hover:underline">
                                                View
                                            </button>
                                        @else
                                            <span class="text-gray-400">None</span>
                                        @endif
                                    </td>


                                        {{-- Approved By --}}
                                        <td class="px-4 py-3">
                                            {{ optional($c->approvedBy)->name ?? '—' }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-sm rounded
                                                @if($c->status === 'approved') bg-green-100 text-green-800
                                                @elseif($c->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($c->status) }}
                                            </span>
                                        </td>

                                        {{-- Notes --}}
                                        <td class="px-4 py-3">{{ $c->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $contributions->links() }}
                    </div>
                @endif
            </main>
        </div>

        {{-- Footer --}}
        @include('mumbos::layouts.partials.footer')
    </div>
<!-- Modal for Receipt Preview -->
<div id="receiptModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg max-w-3xl w-full relative">
        <!-- Close button -->
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>

        <h2 class="text-lg font-bold mb-4">Payment Receipt</h2>

        <!-- Image preview -->
        <img id="receiptImage" src="" alt="Receipt Image"
             class="w-full object-contain max-h-[150vh] hidden">

        <!-- PDF preview -->
        <iframe id="receiptPDF" src="" class="w-full h-[150vh] hidden" frameborder="0"></iframe>
    </div>
</div>

</x-shop::layouts>
<script>
    function openModal(url) {
        const isPDF = url.toLowerCase().endsWith('.pdf');

        // Hide both previews
        document.getElementById('receiptImage').classList.add('hidden');
        document.getElementById('receiptPDF').classList.add('hidden');

        if (isPDF) {
            document.getElementById('receiptPDF').src = url;
            document.getElementById('receiptPDF').classList.remove('hidden');
        } else {
            document.getElementById('receiptImage').src = url;
            document.getElementById('receiptImage').classList.remove('hidden');
        }

        document.getElementById('receiptModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('receiptModal').classList.add('hidden');
        document.getElementById('receiptImage').src = '';
        document.getElementById('receiptPDF').src = '';
    }
</script>
