<x-admin::layouts>
    <div class="mt-8 flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Share Classes</h1>
        <a
            href="{{ route('admin.shares.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded shadow-sm transition"
        >
            + Add Share Class
        </a>
    </div>

    <div class="w-full bg-white shadow-lg rounded-2xl overflow-hidden">
        <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-600 text-white">
                <tr>
                    <th class="px-6 py-3 border text-left">#</th>
                    <th class="px-6 py-3 border text-left">Class</th>
                    <th class="px-6 py-3 border text-left">Origin</th>
                    <th class="px-6 py-3 border text-left">Units</th>
                    <th class="px-6 py-3 border text-left">Description</th>
                    <th class="px-6 py-3 border text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white text-black divide-y divide-gray-200">
                @php
                    $count = $shares->total() - ($shares->currentPage() - 1) * $shares->perPage();
                @endphp

                @forelse($shares as $share)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 border font-semibold">
                            {{ $count-- }}
                        </td>
                        <td class="px-6 py-3 border">{{ $share->class }}</td>
                        <td class="px-6 py-3 border ">{{ $share->origin ?? '-' }}</td>
                        <td class="px-6 py-3 border">{{ $share->units }}</td>
                        <td class="px-6 py-3 border">{{ Str::limit($share->description, 40) }}</td>
                        <td class="px-6 py-3 border">
                            <div class="flex items-center gap-3">
                                {{-- View --}}
                                <a href="{{ route('admin.shares.show', $share) }}" title="View" class="text-blue-600 hover:text-blue-800">
                                    <x-heroicon-s-eye class="w-5 h-5" />
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.shares.edit', $share) }}" title="Edit" class="text-yellow-500 hover:text-yellow-700">
                                    <x-heroicon-s-pencil-square class="w-5 h-5" />
                                </a>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.shares.destroy', $share) }}"
                                    onsubmit="return confirm('Delete this share class?')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="text-red-600 hover:text-red-800">
                                        <x-heroicon-s-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-red-600">No share classes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $shares->links() }}
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>
</x-admin::layouts>
