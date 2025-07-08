<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Incentives</h2>
        <a href="{{ route('admin.incentives.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            New Incentive
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Shareholder</th>
                    <th class="px-4 py-3 text-left font-semibold">Type</th>
                    <th class="px-4 py-3 text-left font-semibold">Units</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($incentives as $incentive)
                    <tr>
                        <td class="px-4 py-3">{{ $incentive->id }}</td>
                        <td class="px-4 py-3">
                            {{ $incentive->shareholder->full_name 
                               ?? $incentive->shareholder->customer->first_name }}
                        </td>
                        <td class="px-4 py-3">{{ ucfirst($incentive->type) }}</td>
                        <td class="px-4 py-3">{{ $incentive->units }}</td>
                        <td class="px-4 py-3">{{ $incentive->description }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-x-2">
                                <a href="{{ route('admin.incentives.edit', $incentive) }}"
                                   class="text-blue-600 hover:text-blue-800 flex items-center space-x-1" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-7.414a2 2 0 112.828 2.828L11 19l-4 1 1-4 9.586-9.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <form method="POST" action="{{ route('admin.incentives.destroy', $incentive) }}"
                                      class="inline-block" onsubmit="return confirm('Delete this incentive?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 flex items-center space-x-1" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                            No incentives found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $incentives->links() }}
    </div>
</x-admin::layouts>
