<x-admin::layouts>
    {{-- Header --}}
    <div class="bg-white mt-8 mb-6 flex items-center justify-between px-6">
        <h1 class="text-2xl font-semibold text-gray-900">Phases</h1>
        <a href="{{ route('admin.phases.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            Add New Phase
        </a>
    </div>

<div class="w-full bg-white rounded-2xl shadow-lg overflow-x-auto px-6">
    <table class="w-full min-w-full text-sm text-gray-800 border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="px-6 py-3 text-left font-semibold border border-gray-300">#</th>
                <th class="px-6 py-3 text-left font-semibold border border-gray-300">Name</th>
                <th class="px-6 py-3 text-left font-semibold border border-gray-300">Value (KES/share)</th>
                <th class="px-6 py-3 text-left font-semibold border border-gray-300">Description</th>
                <th class="px-6 py-3 text-left font-semibold border border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse($phases as $phase)
                <tr class="hover:bg-gray-100 hover:shadow-sm transition">
                    <td class="px-6 py-4 border border-gray-200">{{ $phase->id }}</td>
                    <td class="px-6 py-4 border border-gray-200 font-medium">{{ $phase->name }}</td>
                    <td class="px-6 py-4 border border-gray-200">{{ number_format($phase->share_value, 2) }}</td>
                    <td class="px-6 py-4 border border-gray-200">{{ $phase->description }}</td>
                    <td class="px-6 py-4 border border-gray-200">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.phases.edit', $phase) }}"
                               class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 transition"
                               title="Edit">
                                <x-heroicon-s-pencil class="w-5 h-5" />
                                <span class="hidden sm:inline">Edit</span>
                            </a>

                            <form method="POST" action="{{ route('admin.phases.destroy', $phase) }}"
                                  class="inline-block" onsubmit="return confirm('Delete this phase?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 inline-flex items-center gap-1 transition"
                                        title="Delete">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                    <span class="hidden sm:inline">Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 border border-gray-200">
                        No phases found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


    {{-- Pagination --}}
    <div class="px-6 mt-6">
        {{ $phases->links() }}
    </div>


        <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

</x-admin::layouts>
