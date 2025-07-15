<x-admin::layouts>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Contributions</h2>
        <a href="{{ route('admin.contributions.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            <x-heroicon-s-plus class="w-4 h-4 mr-2" />
            New Contribution
        </a>
    </div>

    @if(session('message'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 mx-4 px-4 py-3 bg-green-100 text-green-800 border border-green-300 rounded transition"
    >
        {{ session('message') }}
    </div>
@endif


    {{-- Search + Table --}}
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
                const term = this.search.toLowerCase();
                let name = row.dataset.name || '';
                let method = row.dataset.method || '';
                let reference = row.dataset.reference || '';
                let status = row.dataset.status || '';
                const match = term === '' || name.includes(term) || method.includes(term) || reference.includes(term) || status.includes(term);
                if (match) this.visibleCount++;
                return match;
            }
        }"
        x-init="$watch('search', () => visibleCount = 0)"
    >

        {{-- Form --}}
        <form id="bulkActionForm" method="POST" action="{{ route('admin.contributions.bulk-action') }}">
            @csrf

            {{-- Search + Bulk Action Bar --}}
            <div class="flex flex-wrap items-center gap-4 px-6 py-4 border-b border-gray-200">
                <input type="text"
                       x-model.debounce.300="search"
                       placeholder="Search by name, method, reference, status..."
                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">

                <select name="action" id="bulkActionSelect"
                        class="border border-gray-300 rounded px-3 py-2 text-sm"
                        required>
                    <option value="">Action</option>
                    <option value="approve">Approve</option>
                    <option value="reject">Reject</option>
                    <option value="delete">Delete</option>
                </select>

                <button type="button"
                        onclick="openConfirmModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    Apply
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-300">
                    <thead class="bg-gray-600 text-white">
                        <tr>
                            <th class="px-4 py-2 border">
                                <input type="checkbox" id="select-all"
                                       onclick="document.querySelectorAll('.contribution-checkbox').forEach(cb => cb.checked = this.checked)">
                            </th>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Shareholder</th>
                            <th class="px-4 py-2 border">Amount</th>
                            <th class="px-4 py-2 border">Phase</th>
                            <th class="px-4 py-2 border">Method</th>
                            <th class="px-4 py-2 border">Reference</th>
                            <th class="px-4 py-2 border">Payment</th>
                            <th class="px-4 py-2 border">Date</th>
                            <th class="px-4 py-2 border">Approval</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y" x-init="visibleCount = 0">
                        @forelse($contributions as $c)
                            <tr x-show="matches($el)"
                                data-name="{{ strtolower(optional($c->shareholder?->customer)?->full_name) }}"
                                data-method="{{ strtolower($c->payment_method) }}"
                                data-reference="{{ strtolower($c->payment_reference ?? '') }}"
                                data-status="{{ strtolower($c->payment_status) }}"
                                class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border">
                                    <input type="checkbox" name="selected_contributions[]" value="{{ $c->id }}" class="contribution-checkbox">
                                </td>
                                <td class="px-4 py-2 border">{{ $c->id }}</td>
                                <td class="px-4 py-2 border">
                                    <span x-html="highlight(`{{ optional($c->shareholder?->customer)?->full_name ?? 'N/A' }}`)"></span>
                                </td>
                                <td class="px-4 py-2 border">{{ number_format($c->amount, 2) }} {{ $c->currency }}</td>
                                <td class="px-4 py-2 border">{{ $c->phase->name ?? '–' }}</td>
                                <td class="px-4 py-2 border">
                                    <span x-html="highlight(`{{ ucfirst(str_replace('_',' ',$c->payment_method)) }}`)"></span>
                                </td>
                                <td class="px-4 py-2 border">
                                    <span x-html="highlight(`{{ $c->payment_reference ?? '-' }}`)"></span>
                                </td>
                                <td class="px-4 py-2 border">
                                    <span x-html="highlight(`{{ ucfirst($c->payment_status) }}`)"
                                          class="text-xs font-semibold px-2 py-1 rounded
                                            {{ $c->payment_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $c->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $c->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    </span>
                                </td>
                                <td class="px-4 py-2 border">{{ $c->contributed_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 border">
                                    @if($c->status === 'pending')
                                        <span class="text-yellow-600 font-semibold text-xs">Pending</span>
                                    @else
                                        <span class="text-xs font-semibold {{ $c->status === 'approved' ? 'text-green-700' : 'text-gray-400' }}">
                                            {{ strtoupper($c->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr x-show="visibleCount === 0">
                                <td colspan="10" class="text-center text-red-500 py-4">No contributions found.</td>
                            </tr>
                        @endforelse
                        <tr x-show="visibleCount === 0 && search.length > 0">
                            <td colspan="10" class="text-center text-gray-500 italic py-4">
                                No contributions match your search.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $contributions->links() }}
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-md shadow-md p-5 w-[400px] text-sm">
            <h2 class="text-base font-semibold text-gray-800 mb-2">Confirm Bulk Action</h2>
            <p class="text-gray-700 mb-4">Are you sure you want to apply the selected action to the selected contributions?</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeConfirmModal()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900">Cancel</button>
                <button onclick="submitBulkForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 text-sm rounded">
                    Yes, Apply
                </button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

    {{-- Scripts --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function openConfirmModal() {
            const action = document.getElementById('bulkActionSelect').value;
            if (!action) {
                alert('Please select an action.');
                return;
            }

            const selected = document.querySelectorAll('.contribution-checkbox:checked');
            if (selected.length === 0) {
                alert('Please select at least one contribution.');
                return;
            }

            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function submitBulkForm() {
            document.getElementById('bulkActionForm').submit();
        }
    </script>

</x-admin::layouts>
