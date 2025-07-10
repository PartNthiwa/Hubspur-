


<x-admin::layouts>


    <div class="mb-2 mt-8 flex items-center justify-between px-6">
        <h1 class="text-lg font-semibold text-gray-800">Members List</h1>
      
        <a href="{{ route('admin.shareholders.create') }}"
           class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            <span class="text-sm font-medium">Add Member</span>
        </a>
    </div>


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
                let name = row.dataset.name?.toLowerCase() || '';
                let number = row.dataset.number?.toLowerCase() || '';
                let matched = term === '' || name.includes(term) || number.includes(term);

                if (matched) this.visibleCount++;
                return matched;
            }
        }"
        x-init="$watch('search', () => visibleCount = 0)"
    >
        {{-- Filters --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <input type="text"
                   x-model.debounce.300="search"
                   placeholder="Search members by name or number…"
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

   
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 border border-gray-300 text-left">#</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Member Number</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Full Name</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Membership Types</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Membership Paid</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Capital Contribution</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Incentives</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Capital Shares</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Total Shares</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Total Contributions</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Status</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white" x-init="visibleCount = 0">
                    @forelse($shareholders as $shareholder)
                        @php
                            $fullName = $shareholder->customer->first_name . ' ' . $shareholder->customer->last_name;
                            $membershipContribution = $shareholder->contributions->where('type', 'membership')->where('status', 'approved')->sum('amount');
                            $capitalContribution = $shareholder->contributions->where('type', 'capital')->where('status', 'approved')->sum('amount');
                            $nonMembershipTotal = $shareholder->contributions->filter(fn($c) => strtolower(trim($c->type)) !== 'membership' && $c->status === 'approved')->sum('amount');
                            $shareValue = $shareholder->phase->share_value ?? 1000;
                            $contributionShares = $shareValue > 0 ? $nonMembershipTotal / $shareValue : 0;
                            $incentiveShares = $shareholder->incentives->sum(fn($i) => $i->pivot->units ?? 0);

                            $totalShares = $contributionShares + $incentiveShares;
                        @endphp

                        <tr x-show="matches($el)"
                            data-name="{{ strtolower($fullName) }}"
                            data-number="{{ strtolower($shareholder->shareholder_number) }}"
                            class="hover:bg-gray-100 hover:shadow-sm transition">
                            <td class="px-6 py-4 border">{{ $shareholder->id }}</td>
                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ $shareholder->shareholder_number }}`)"></span>
                            </td>
                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ $fullName }}`)"></span>
                            </td>
                            <td class="px-6 py-4 border">
                                @forelse ($shareholder->membershipTypes as $membership)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mr-1">
                                        {{ $membership->type }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-sm">–</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 border">KES {{ number_format($membershipContribution, 2) }}</td>
                            <td class="px-6 py-4 border">KES {{ number_format($capitalContribution, 2) }}</td>
                         <td class="px-4 py-3">
    @forelse ($shareholder->incentives as $incentive)
       <form
    method="POST"
    action="{{ route('admin.incentives.update-units', ['incentive_id' => $incentive->id, 'shareholder_number' => $shareholder->shareholder_number]) }}"
    class="inline-flex items-center gap-1 bg-indigo-100 text-black text-xs px-2 py-1 rounded mr-1"
>
    @csrf
    @method('PUT')

    <label class="font-semibold mr-1">{{ ucfirst($incentive->type) }}:</label>

    <input
        type="number"
        name="units"
        value="{{ $incentive->pivot->units ?? 0 }}"
        min="0"
        class="w-14 text-xs border border-gray-300 rounded px-1 py-0.5"
    />

    <button type="submit"
            class="text-green-700 hover:text-green-900 text-xs font-semibold border border-green-600 rounded px-2 py-0.5 bg-white">
        Update
    </button>
</form>

    @empty
        <span class="text-gray-400 text-sm">–</span>
    @endforelse
</td>



                            <td class="px-6 py-4 border">{{ number_format($contributionShares) }}</td>
                            <td class="px-6 py-4 border">{{ number_format($totalShares) }}</td>
                            <td class="px-6 py-4 border">KES {{ number_format($nonMembershipTotal, 2) }}</td>
                            <td class="px-6 py-4 border">
                                <span class="text-xs px-2 py-1 rounded {{ $shareholder->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                    {{ $shareholder->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border">
                                <div class="flex items-center gap-x-3">
                                    <a href="{{ route('admin.shareholders.show', $shareholder) }}"
                                       class="text-indigo-600 hover:text-indigo-800" title="View">
                                        <x-heroicon-s-eye class="w-5 h-5" />
                                    </a>
                                    <a href="{{ route('admin.shareholders.edit', $shareholder) }}"
                                       class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.shareholders.destroy', $shareholder->shareholder_number) }}"
                                          onsubmit="return confirm('Delete this shareholder?')"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="text-red-600 hover:text-red-800">
                                            <x-heroicon-s-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr x-show="visibleCount === 0">
                            <td colspan="12" class="px-6 py-4 text-center text-red-600">
                                Whoops!! No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $shareholders->links() }}
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin::layouts>
