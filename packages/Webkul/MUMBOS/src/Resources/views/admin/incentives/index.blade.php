<x-admin::layouts>
    <div class="mb-2 mt-8 flex items-center justify-between px-6">
        <p class="flex-1 text-gray-700">
            Incentives are bonus units or benefits awarded to shareholders for specific actions or milestones.
        </p>
        <a href="{{ route('admin.incentives.create') }}"
           class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            <span class="text-sm font-medium">Assign a New Incentive</span>
        </a>
    </div>

    <div
        class="w-full bg-white shadow-lg rounded-2xl overflow-hidden"
        x-data="{
            search: '',
            type: '',
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
                let desc = row.dataset.desc?.toLowerCase() || '';
                let type = row.dataset.type?.toLowerCase() || '';
                let matchType = this.type === '' || type === this.type;
                let matched = matchType && (
                    term === '' || name.includes(term) || desc.includes(term) || type.includes(term)
                );

                if (matched) this.visibleCount++;
                return matched;
            }
        }"
        x-init="$watch('search', () => visibleCount = 0); $watch('type', () => visibleCount = 0)"
    >

        {{-- Filters --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <select x-model="type"
                    class="w-40 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
                <option value="">All Types</option>
                <option value="bonus">Bonus</option>
                <option value="reward">Reward</option>
                <option value="gift">Gift</option>
                <option value="recognition">Recognition</option>
                <option value="referral">Referral</option>
            </select>

            <input type="text"
                   x-model.debounce.300="search"
                   placeholder="Search incentives…"
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        {{-- Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">#</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Shareholder</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Type</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Units</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Description</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white" x-init="visibleCount = 0">
                    @forelse($incentives as $incentive)
                        @foreach ($incentive->shareholders as $shareholder)
                            @php
                                $name = $shareholder->full_name
                                    ?? optional($shareholder->customer)->first_name
                                    ?? 'N/A';
                            @endphp
                            <tr
                                x-show="matches($el)"
                                data-name="{{ $name }}"
                                data-desc="{{ $incentive->description }}"
                                data-type="{{ strtolower($incentive->type) }}"
                                class="hover:bg-gray-100 hover:shadow-sm transition"
                            >
                                <td class="px-6 py-4 border border-gray-200">{{ $incentive->id }}</td>
                                <td class="px-6 py-4 border border-gray-200">
                                    <span x-html="highlight(`{{ $name }}`)"></span>
                                </td>
                                <td class="px-6 py-4 border border-gray-200 capitalize">
                                    <span x-html="highlight(`{{ strtolower($incentive->type) }}`)"></span>
                                </td>
                                <td class="px-6 py-4 border border-gray-200">{{ $shareholder->pivot->units }}</td>
                                <td class="px-6 py-4 border border-gray-200">
                                    <div class="truncate max-w-sm" title="{{ $incentive->description }}">
                                        <span x-html="highlight(`{{ \Str::limit($incentive->description, 80) }}`)"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 border border-gray-200">
                                    <div class="flex items-center gap-x-4">
                                        <a href="{{ route('admin.incentives.edit', $incentive) }}"
                                           class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                            <x-heroicon-s-pencil class="w-5 h-5" />
                                        </a>
                                        <form method="POST" action="{{ route('admin.incentives.destroy', $incentive) }}"
                                              onsubmit="return confirm('Delete this incentive?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition"
                                                    title="Delete">
                                                <x-heroicon-s-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                    @endforelse

                    {{-- Empty message --}}
                    <tr x-show="visibleCount === 0">
                        <td colspan="6" class="px-6 py-4 text-center text-red-600">
                           Whoops!! No incentives found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $incentives->links() }}
        </div>
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin::layouts>
