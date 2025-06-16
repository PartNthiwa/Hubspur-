<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Contribution #{{ $contribution->id }}</h2>
        <a href="{{ route('admin.contributions.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-6">
            {{-- Shareholder --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Shareholder</h3>
                <p class="mt-1 text-gray-800">
                    {{ $contribution->shareholder->customer->first_name }}
                    {{ $contribution->shareholder->customer->last_name }}
                </p>
            </div>

            {{-- Amount & Currency --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Amount</h3>
                <p class="mt-1 text-gray-800">
                    {{ number_format($contribution->amount, 2) }} {{ $contribution->currency }}
                </p>
            </div>

            {{-- Payment Method --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Payment Method</h3>
                <p class="mt-1 text-gray-800">
                    {{ $contribution->payment_method_label }}
                </p>
            </div>

            {{-- Payment Status --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Payment Status</h3>
                <p class="mt-1">
                    @if($contribution->payment_status === 'completed')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Completed</span>
                    @elseif($contribution->payment_status === 'failed')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Failed</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                    @endif
                </p>
            </div>

            {{-- Contributed At --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Contributed At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $contribution->contributed_at->format('Y-m-d') }}
                </p>
            </div>

            {{-- Recorded By --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Recorded By</h3>
                <p class="mt-1 text-gray-800">
                    {{ optional($contribution->recordedBy)->name ?? '—' }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600">Approval Status</h3>
                <p class="mt-1">
                    @if($contribution->status === 'approved')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Approved</span>
                    @elseif($contribution->status === 'rejected')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Rejected</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                    @endif
                </p>
            </div>

            {{-- Approved By --}}
            @if($contribution->approved_at)
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">Approved By</h3>
                    <p class="mt-1 text-gray-800">
                        {{ optional($contribution->approvedBy)->name ?? '—' }}
                        <br>
                        <span class="text-gray-500 text-xs">{{ $contribution->approved_at->format('Y-m-d H:i') }}</span>
                    </p>
                </div>
            @endif

            <div class="col-span-2">
                <h3 class="text-sm font-semibold text-gray-600">Reference / Channel</h3>
                <p class="mt-1 text-gray-800">
                    {{ $contribution->payment_channel ?? '—' }}  
                    {{ $contribution->payment_reference ? ' / ' . $contribution->payment_reference : '' }}
                </p>
            </div>

            
            <div class="col-span-2">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Receipt</h3>

   
                <div class="w-full border rounded overflow-hidden mb-3" style="height: 500px;">
                    <iframe src="{{ $contribution->receipt_url }}" class="w-full h-full" frameborder="0"></iframe>
                </div>

                {{-- Download link --}}
                <a href="{{ $contribution->receipt_url }}" target="_blank"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition">
                    Download Receipt PDF
                </a>
            </div>



        </div>
    </div>
</x-admin::layouts>
