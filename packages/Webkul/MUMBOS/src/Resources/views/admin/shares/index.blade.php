<x-admin::layouts>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Shares</h1>
        <a href="{{ route('admin.shares.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded">+ Add Share</a>
    </div>

    <table class="min-w-full bg-white shadow rounded text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Class</th>
                <th class="px-4 py-2 text-left">Units</th>
                <th class="px-4 py-2 text-left">Price/Unit</th>
                <th class="px-4 py-2 text-left">Total</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shares as $share)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $share->class }}</td>
                    <td class="px-4 py-2">{{ $share->units }}</td>
                    <td class="px-4 py-2">Ksh {{ number_format($share->price_per_unit, 2) }}</td>
                    <td class="px-4 py-2">Ksh {{ number_format($share->total_value, 2) }}</td>
                    <td class="px-4 py-2 space-x-2 flex">
                        <a href="{{ route('admin.shares.show', $share) }}" title="View" class="text-indigo-600">👁</a>
                        <a href="{{ route('admin.shares.edit', $share) }}" title="Edit" class="text-blue-600">✏️</a>
                        <form method="POST" action="{{ route('admin.shares.destroy', $share) }}" onsubmit="return confirm('Delete this share?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600">🗑</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No shares found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $shares->links() }}
    </div>
</x-admin::layouts>
