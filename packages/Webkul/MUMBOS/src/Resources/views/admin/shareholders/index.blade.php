<x-admin::layouts>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Shareholders</h1>
        <a href="{{ route('admin.shareholders.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded shadow-sm transition">
            + Add Shareholder
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Member Number</th>
                    <th class="px-4 py-3 text-left font-semibold">Full Name</th>
                    <th class="px-4 py-3 text-left font-semibold">Share Category</th>
                    <th class="px-4 py-3 text-left font-semibold">Capital Paid</th>                  
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($shareholders as $shareholder)
                    <tr>
                        <td class="px-4 py-3">{{ $shareholder->id }}</td>
                         <td class="px-4 py-3">{{ $shareholder->shareholder_number }}</td>
                        <td class="px-4 py-3">{{ $shareholder->customer->first_name }} {{ $shareholder->customer->last_name }}</td>
                        <td class="px-4 py-3">{{ $shareholder->share_class }}</td>
                        <td class="px-4 py-3">{{ $shareholder->capital_paid }}</td>
                        <td class="px-4 py-3">
                            @if ($shareholder->is_active)
                                <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Active</span>
                            @else
                                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded" style="background-color: #dc2626;">Inactive</span>
                            @endif
                        </td>

                      <td class="px-4 py-3">
                        <div class="flex items-center gap-x-4">
                            <a href="{{ route('admin.shareholders.show', ['shareholder' => $shareholder]) }}"
                            class="text-indigo-600 hover:text-indigo-800" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.shareholders.edit', $shareholder) }}"
                            class="text-blue-600 hover:text-blue-800" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-7.414a2 2 0 112.828 2.828L11 19l-4 1 1-4 9.586-9.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.shareholders.destroy', $shareholder->id) }}" class="inline-block"
                                onsubmit="return confirm('Delete this shareholder?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No shareholders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $shareholders->links() }}
    </div>
</x-admin::layouts>
