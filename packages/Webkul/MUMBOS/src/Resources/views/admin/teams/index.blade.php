<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4 px-6">
        <h2 class="text-lg font-semibold">Leadership Teams</h2>
        <a href="{{ route('admin.teams.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            Create Team
        </a>
    </div>

    <div class="px-6">
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Team Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $team->id }}</td>
                            <td class="px-4 py-3">{{ $team->name }}</td>
                            <td class="px-4 py-3">{{ Str::limit($team->description, 60) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('admin.teams.destroy', $team) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this team?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800" title="Delete">
                                            <x-heroicon-s-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">No teams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $teams->links() }}
        </div>
    </div>
</x-admin::layouts>
