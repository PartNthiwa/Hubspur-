<x-admin::layouts>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Shares</h1>
        <a href="{{ route('admin.shares.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded">+ Add Share</a>
    </div>

    <table class="min-w-full bg-white shadow rounded text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Class</th>
                <th class="px-4 py-2">Units</th>
                <th class="px-4 py-2">Available Units</th>
                <th class="px-4 py-2">Price/Unit</th>
                <th class="px-4 py-2">Total Value</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Visibility</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Icon</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shares as $share)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $share->class }}</td>
                    <td class="px-4 py-2">{{ $share->units }}</td>
                    <td class="px-4 py-2">{{ $share->available_units }}</td>
                    <td class="px-4 py-2">Ksh {{ number_format($share->price_per_unit, 2) }}</td>
                    <td class="px-4 py-2">Ksh {{ number_format($share->total_value, 2) }}</td>
                    <td class="px-4 py-2">
                        @if($share->is_active)
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">Active</span>
                        @else
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ ucfirst($share->visibility) }}</td>
                    <td class="px-4 py-2">{{ Str::limit($share->description, 30) }}</td>
                    <td class="px-4 py-2">
                        @if ($share->icon_url)
                            <img src="{{ asset('storage/' . $share->icon_url) }}" alt="Icon"
                                 class="w-8 h-8 object-cover rounded">
                        @else
                            <span class="text-gray-400 italic">No Icon</span>
                        @endif
                    </td>
                   <td class="px-4 py-2">
                    <div class="flex justify-end gap-6 items-center">
                        <a href="{{ route('admin.shares.show', $share) }}" title="View">
                            <svg class="w-5 h-5 text-indigo-600 hover:text-indigo-800" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.shares.edit', $share) }}" title="Edit">
                            <svg class="w-5 h-5 text-blue-600 hover:text-blue-800" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15.232 5.232l3.536 3.536M9 11l3.536-3.536a2 2 0 112.828 2.828L11 13l-4 1 1-4z" />
                                <path d="M16 21H4a2 2 0 01-2-2V4a2 2 0 012-2h7" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.shares.destroy', $share) }}" onsubmit="return confirm('Delete this share?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Delete">
                                <svg class="w-5 h-5 text-red-600 hover:text-red-800" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>

                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-4 py-4 text-center text-gray-500">No shares found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $shares->links() }}
    </div>
</x-admin::layouts>
