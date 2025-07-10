<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Contributions</h2>
        <a href="{{ route('admin.contributions.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            New Contribution
        </a>
    </div>

    {{-- Search Wrapper --}}
    <div
        class="w-full bg-white shadow-lg rounded-2xl overflow-hidden"
        x-data="{
            search: '',
            visibleCount: 0,
            highlight(text) {
                if (!this.search) return text;
                const term = this.search.toLowerCase();
                const regex = new RegExp(`(${term})`, 'gi');
                return text.replace(regex, '<mark class=\'bg-yellow-200\'>$1</mark>');
            },
            matches(row) {
                let term = this.search.toLowerCase();

                let name = row.dataset.name || '';
                let method = row.dataset.method || '';
                let reference = row.dataset.reference || '';
                let status = row.dataset.status || '';

                let matched = term === '' ||
                    name.includes(term) ||
                    method.includes(term) ||
                    reference.includes(term) ||
                    status.includes(term);

                if (matched) this.visibleCount++;
                return matched;
            }
        }"
        x-init="$watch('search', () => visibleCount = 0)"
    >

        {{-- 🔍 Search Field --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <input type="text"
                   x-model.debounce.300="search"
                   placeholder="Search by name, method, reference, status..."
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        {{-- Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">#</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Shareholder</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Phase</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Method</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Reference</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Payment Status</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Recorded By</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Receipt</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Date</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Actions</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Approval Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" x-init="visibleCount = 0">
                    @forelse($contributions as $c)
                        <tr x-show="matches($el)"
                            data-name="{{ strtolower(optional($c->shareholder?->customer)?->full_name) }}"
                            data-method="{{ strtolower($c->payment_method) }}"
                            data-reference="{{ strtolower($c->payment_reference ?? '') }}"
                            data-status="{{ strtolower($c->payment_status) }}"
                            class="hover:bg-gray-100 transition">
                            <td class="px-6 py-4 border">{{ $c->id }}</td>

                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ optional($c->shareholder?->customer)?->full_name ?? 'N/A' }}`)"></span>
                            </td>

                            <td class="px-6 py-4 border">
                                {{ number_format($c->amount, 2) }} {{ $c->currency }}
                            </td>

                            <td class="px-6 py-4 border">{{ $c->phase->name ?? '–' }}</td>

                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ ucfirst(str_replace('_',' ',$c->payment_method)) }}`)"></span>
                            </td>

                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ $c->payment_reference ?? '-' }}`)"></span>
                            </td>

                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ ucfirst($c->payment_status) }}`)"></span>
                            </td>

                            <td class="px-6 py-4 border">{{ optional($c->recordedBy)?->name ?? '–' }}</td>

                            <td class="px-6 py-4 border">
                                @if($c->receipt_url)
                                    <a href="{{ $c->receipt_url }}" target="_blank" class="text-blue-600 underline">PDF</a>
                                @else
                                    <span class="text-gray-400">–</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 border">{{ $c->contributed_at->format('Y-m-d') }}</td>

                            <td class="px-6 py-4 border">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.contributions.show', $c) }}"
                                       class="text-indigo-600 hover:text-indigo-800" title="View">
                                        <x-heroicon-s-eye class="w-5 h-5" />
                                    </a>

                                    <form method="POST" action="{{ route('admin.contributions.destroy', $c) }}"
                                          onsubmit="return confirm('Delete this contribution?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <x-heroicon-s-trash class="w-5 h-5" />
                                        </button>
                                    </form>

                                    @if($c->payment_method === 'mpesa' && $c->payment_status === 'pending')
                                        <form action="{{ route('admin.contributions.recheck', $c) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                                                Recheck
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 border">
                                @if($c->status === 'pending')
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.contributions.approve', $c) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                style="background-color: #16a34a; color: white; padding: 0.25rem 0.75rem; font-size: 0.75rem; border-radius: 0.25rem;"
                                                onmouseover="this.style.backgroundColor='#15803d'"
                                                onmouseout="this.style.backgroundColor='#16a34a'"
                                            >
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.contributions.reject', $c) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                style="background-color: #dc2626; color: white; padding: 0.25rem 0.75rem; font-size: 0.75rem; border-radius: 0.25rem;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#dc2626'"
                                            >
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs font-semibold {{ $c->status === 'approved' ? 'text-green-600' : ($c->status === 'failed' ? 'text-red-600' : 'text-gray-300') }}">
                                        {{ strtoupper($c->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr x-show="visibleCount === 0">
                            <td colspan="12" class="px-6 py-4 text-center text-red-500">No contributions found.</td>
                        </tr>
                    @endforelse

                        <tr x-show="visibleCount === 0 && search.length > 0">
                        <td colspan="12" class="px-6 py-4 text-center text-gray-500 italic">
                            No contributions match your search.
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $contributions->links() }}
        </div>
    </div>

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin::layouts>
