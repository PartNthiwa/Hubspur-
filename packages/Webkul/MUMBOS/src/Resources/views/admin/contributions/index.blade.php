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

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Shareholder</th>
                    <th class="px-4 py-3 text-left font-semibold">Amount</th>
                    <th class="px-4 py-3 text-left font-semibold">Method</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Date</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($contributions as $c)
                    <tr>
                        <td class="px-4 py-3">{{ $c->id }}</td>
                        <td class="px-4 py-3">{{ $c->shareholder->customer->first_name }} {{ $c->shareholder->customer->last_name }}</td>
                        <td class="px-4 py-3">{{ number_format($c->amount, 2) }} {{ $c->currency }}</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ',$c->payment_method)) }}</td>
                        <td class="px-4 py-3">
                            @if($c->payment_status == 'completed')
                                <span class="bg-green-100 text-green-900 px-2 py-1 rounded text-xs">Completed</span>
                            @elseif($c->payment_status == 'failed')
                                <span class="bg-red-100 text-red-900 px-2 py-1 rounded text-xs">Failed</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-900 px-2 py-1 rounded text-xs">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $c->contributed_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                                  <div class="flex items-center gap-x-4">

                                <a href="{{ route('admin.contributions.show', $c) }}"
                                   class="text-indigo-600 hover:text-indigo-800 flex items-center space-x-1" title="View">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 " fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                         
                                <a href="{{ route('admin.contributions.edit', $c) }}"
                                   class="text-blue-600 hover:text-blue-800 flex items-center space-x-1" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-7.414a2 2 0 112.828 2.828L11 19l-4 1 1-4 9.586-9.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                           
                                <form method="POST" action="{{ route('admin.contributions.destroy', $c) }}"
                                      class="inline-block" onsubmit="return confirm('Delete this contribution?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 flex items-center space-x-1" title="Delete">
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
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No contributions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contributions->links() }}
    </div>
</x-admin::layouts>
