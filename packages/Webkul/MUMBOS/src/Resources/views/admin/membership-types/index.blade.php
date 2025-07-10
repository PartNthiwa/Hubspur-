<x-admin::layouts>
    <div class="mb-2 mt-8 flex items-center justify-between px-1">
       <p class="bg-green-100 border-l-4 border-red-600 text-yellow-800 p-4 rounded shadow-sm">
            <strong>Note:</strong>  Membership Types determine the share value and benefits for each member class.
        </p>
        <a
            href="{{ route('admin.membership-types.create') }}"
            class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition"
        >
            <x-heroicon-s-plus class="w-5 h-5" />
            <span class="text-sm font-medium">Add New Type</span>
        </a>
    </div>

    <div
        class="w-full bg-white shadow-lg rounded-2xl overflow-hidden"
        x-data="{
            search: '',
            status: '',
            matches(row) {
                let term = this.search.toLowerCase();
                let inName = row.dataset.name.toLowerCase().includes(term);
                let inDesc = row.dataset.desc.toLowerCase().includes(term);
                let statusMatch = this.status === '' || row.dataset.status === this.status;
                return statusMatch && (term === '' || inName || inDesc);
            }
        }"
    >
        {{-- Filters --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <select
                x-model="status"
                class="w-32 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            >
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <input
                type="text"
                x-model.debounce.300="search"
                placeholder="Search membership types…"
                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            />
        </div>

        {{-- Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Name</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Description</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Status</th>
                        <th class="px-6 py-3 text-left font-semibold border border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($membershipTypes as $type)
                        <tr
                            x-show="matches($el)"
                            data-name="{{ $type->type }}"
                            data-desc="{{ $type->description }}"
                            data-status="{{ $type->is_active ? '1' : '0' }}"
                            class="hover:bg-gray-50 transition"
                        >
                            <td class="px-6 py-4 border">{{ $type->type }}</td>
                            <td class="px-6 py-4 border">KES {{ number_format($type->share_value) }}</td>
                            <td class="px-6 py-4 border">
                                <div class="truncate max-w-md" title="{{ $type->description }}">
                                    {{ \Str::limit($type->description, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 border">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $type->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.membership-types.edit', $type) }}"
                                       class="hover:text-blue-700 transition" title="Edit">
                                        <x-heroicon-s-pencil class="w-5 h-5 text-blue-600" />
                                    </a>
                                    <form action="{{ route('admin.membership-types.destroy', $type) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this Membership type? This action cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete" class="hover:text-red-700 transition">
                                            <x-heroicon-s-trash class="w-5 h-5 text-red-600" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-red-600">
                                No membership types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $membershipTypes->links() }}
        </div>
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin::layouts>
