<x-shop::layouts
    :title="__('My Contributions')"
    :has-header="false"
    :has-footer="false"
>
    <div class="flex flex-col min-h-screen">
        @include('mumbos::layouts.partials.admin-header')

        <div class="flex flex-1">
            @include('mumbos::layouts.partials.admin-sidebar')

            <main class="flex-1 p-6 bg-gray-50">
                <h1 class="text-2xl font-bold mb-6">{{ __('My Contributions') }}</h1>
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
    {{-- Active Phase Info --}}
    @if ($activePhase)
        <div class="text-gray-700 space-y-2">
            <p class="text-sm font-medium text-gray-500">Active Contribution Phase</p>
            <p class="text-lg font-semibold">{{ $activePhase->name }}</p>

            <div 
                x-data="{
                    countdown: '',
                    init() {
                        const target = new Date('{{ $activePhase->ends_at }}').getTime();
                        const update = () => {
                            const now = new Date().getTime();
                            const distance = target - now;
                            if (distance <= 0) {
                                this.countdown = 'Closed';
                                return;
                            }
                            const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const s = Math.floor((distance % (1000 * 60)) / 1000);
                            this.countdown = `${d}d ${h}h ${m}m ${s}s`;
                        };
                        update();
                        setInterval(update, 1000);
                    }
                }"
                x-init="init()"
                class="text-2xl font-bold text-blue-600"
                x-text="countdown"
            ></div>
        </div>
    @else
        <div class="text-gray-600 italic">No active contributions are running. <br>
            <small style="color:red;">if you think this is a mistake contact support</small>
        </div>
    @endif

    {{-- Button --}}
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('shop.shareholders.contributions.create') }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            + {{ __('Make Contribution') }}
        </a>
    </div>
</div>



                @if ($contributions->isEmpty())
                    <p class="text-gray-600">{{ __('You have not made any contributions yet.') }}</p>
                @else
                    <div
                        x-data="{
                            search: '',
                            matches(row) {
                                const term = this.search.toLowerCase();
                                return row.dataset.search.toLowerCase().includes(term);
                            }
                        }"
                    >
                        <div class="mb-4">
                            <input
                                type="text"
                                x-model.debounce.300="search"
                                placeholder="Search by method, reference, notes, status..."
                                class="w-full sm:w-1/3 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-300"
                            />
                        </div>

                        {{-- Summary Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            {{-- Membership Contribution --}}
                            <div class="bg-blue-100 text-blue-900 p-5 rounded-2xl shadow flex items-center space-x-4">
                                <div class="p-2 bg-blue-200 text-blue-700 rounded-full">
                                    <x-heroicon-o-user-group class="w-6 h-6"/>
                                </div>
                                <div>
                                    <p class="text-sm">Membership Contribution</p>
                                    <p class="text-xl font-semibold">{{ number_format($membershipContribution, 2) }}</p>
                                </div>
                            </div>

                            {{-- Capital Contribution --}}
                            <div class="bg-green-100 text-green-900 p-5 rounded-2xl shadow flex items-center space-x-4">
                                <div class="p-2 bg-green-200 text-green-700 rounded-full">
                                    <x-heroicon-o-banknotes class="w-6 h-6"/>
                                </div>
                                <div>
                                    <p class="text-sm">Capital Contribution</p>
                                    <p class="text-xl font-semibold">{{ number_format($capitalContribution, 2) }}</p>
                                </div>
                            </div>

                            {{-- Other Contributions --}}
                            <div class="bg-yellow-100 text-yellow-900 p-5 rounded-2xl shadow flex items-center space-x-4">
                                <div class="p-2 bg-yellow-200 text-yellow-700 rounded-full">
                                    <x-heroicon-o-bars-3-bottom-left class="w-6 h-6"/>
                                </div>
                                <div>
                                    <p class="text-sm">Other Contributions</p>
                              
                                    <p class="text-xl font-semibold">{{ number_format($otherContribution, 2) }}</p> 
                                </div>
                            </div>

                            {{-- Total Shares --}}
                            <div class="bg-purple-100 text-purple-900 p-5 rounded-2xl shadow flex items-center space-x-4">
                                <div class="p-2 bg-purple-200 text-purple-700 rounded-full">
                                    <x-heroicon-o-chart-pie class="w-6 h-6"/>
                                </div>
                                <div>
                                    <p class="text-sm">Total Shares</p>
                                    <p class="text-xl font-semibold">{{ number_format($totalShares, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                            {{-- Contribution Shares --}}
                          <div class="bg-indigo-100 text-gray-900 p-5 rounded-2xl shadow flex flex-col space-y-3">
    <div class="flex items-center space-x-4">
        <div class="p-2 bg-indigo-200 text-indigo-700 rounded-full">
            <x-heroicon-o-scale class="w-6 h-6"/>
        </div>
        <div>
            <p class="text-sm">Other Contributions Shares</p>
            <p class="text-xl font-semibold">{{ number_format($otherContribution, 2) }}</p>
        </div>
    </div>

    {{-- Breakdown --}}
    <div class="ml-2 space-y-1 border-t border-indigo-300 pt-2">
        @php
            $otherTypes = $shareholder->contributions()
                ->approved()
                ->whereNotIn('type', ['membership', 'capital'])
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->orderBy('type')
                ->get();
        @endphp

        @forelse ($otherTypes as $item)
            <div class="text-sm text-indigo-800">
                • <span class="font-medium">{{ ucfirst($item->type) }}:</span>
                {{ number_format($item->total, 2) }}
            </div>
        @empty
            <div class="text-sm text-indigo-500 italic">No other contributions.</div>
        @endforelse
    </div>
</div>

{{-- Incentive Shares --}}
<div class="bg-blue-600 text-white p-5 rounded-2xl shadow space-y-3">
    <div class="flex items-center space-x-4">
        <div class="p-2 bg-rose-200 text-white rounded-full">
            <x-heroicon-o-gift class="w-6 h-6"/>
        </div>
        <div>
            <p class="text-sm">Incentive Shares</p>
            <p class="text-xl font-semibold">{{ number_format($incentiveShares, 2) }}</p>
        </div>
    </div>

    <div class="ml-2 space-y-1 border-t border-rose-300 pt-2">
        @forelse ($shareholder->incentives->sortBy('type') as $incentive)
            <div class="text-sm text-rose-800">
                • <span class="font-medium">{{ ucfirst($incentive->type) }}:</span>
                {{ number_format($incentive->pivot->units ?? 0) }} shares
            </div>
        @empty
            <div class="text-sm text-rose-500 italic">No incentive shares yet.</div>
        @endforelse
    </div>
</div>

{{-- Assigned Shares --}}
<div class="bg-red-600 text-white p-5 rounded-2xl shadow space-y-3">
    <div class="flex items-center space-x-4">
        <div class="p-2 bg-orange-200 text-white rounded-full">
            <x-heroicon-o-user-circle class="w-6 h-6"/>
        </div>
        <div>
            <p class="text-sm">Assigned Shares</p>
            <p class="text-xl font-semibold">{{ number_format($assignedShares, 2) }}</p>
        </div>
    </div>

    <div class="ml-2 space-y-1 border-t border-orange-300 pt-2">
        @forelse ($shareholder->shares->sortBy('class') as $share)
            <div class="text-sm text-orange-800">
                • <span class="font-medium">{{ ucfirst($share->class) }}:</span>
                {{ number_format($share->pivot->units ?? 0) }} shares
            </div>
        @empty
            <div class="text-sm text-orange-500 italic">No assigned shares.</div>
        @endforelse
    </div>
</div>


                        </div>

                        {{-- Contributions Table --}}
                        <div class="overflow-x-auto bg-white shadow rounded-lg">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Date</th>
                                        <th class="px-4 py-3 text-left">Amount</th>
                                        <th class="px-4 py-3 text-left">Method</th>
                                        <th class="px-4 py-3 text-left">Reference</th>
                                        <th class="px-4 py-3 text-left">Receipt</th>
                                        <th class="px-4 py-3 text-left">Approved By</th>
                                        <th class="px-4 py-3 text-left">Type</th>
                                        <th class="px-4 py-3 text-left">Phase</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                        <th class="px-4 py-3 text-left">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y text-gray-700">
                                    @foreach ($contributions as $c)
                                        <tr
                                            x-show="matches($el)"
                                            data-search="{{ $c->payment_method }} {{ $c->payment_reference }} {{ $c->note }} {{ $c->status }}"
                                        >
                                            <td class="px-4 py-3">{{ $c->contributed_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-3">{{ $c->currency }} {{ number_format($c->amount, 2) }}</td>
                                            <td class="px-4 py-3">{{ ucfirst($c->payment_method) }}</td>
                                            <td class="px-4 py-3">{{ $c->payment_reference ?? '—' }}</td>
                                            <td class="px-4 py-3">
                                                @if ($c->payment_receipt)
                                                    <button onclick="openModal('{{ Storage::url($c->receipt_url) }}')"
                                                            class="text-blue-600 hover:underline">
                                                        View
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">None</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">{{ optional($c->approvedBy)->name ?? '—' }}</td>
                                            <td class="px-4 py-3 capitalize">{{ $c->type ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ optional($c->phase)->title ?? '—' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-sm rounded
                                                    @if($c->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($c->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($c->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">{{ $c->note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $contributions->links() }}
                        </div>
                    </div>
                @endif
            </main>
        </div>

        @include('mumbos::layouts.partials.footer')
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg max-w-3xl w-full relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
            <h2 class="text-lg font-bold mb-4">Payment Receipt</h2>
            <img id="receiptImage" src="" alt="Receipt Image" class="w-full object-contain max-h-[150vh] hidden">
            <iframe id="receiptPDF" src="" class="w-full h-[150vh] hidden" frameborder="0"></iframe>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        function openModal(url) {
            const isPDF = url.toLowerCase().endsWith('.pdf');
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
</x-shop::layouts>
