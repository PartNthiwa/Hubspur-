<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4 px-6">
        <h2 class="text-lg font-semibold">Team Members</h2>
        <a href="{{ route('admin.leaders.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            Add Team Member
        </a>
    </div>

    <div class="px-6">
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Full Name</th>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Team</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaders as $leader)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $leader->id }}</td>
                            <td class="px-4 py-3">{{ $leader->name }}</td>
                            <td class="px-4 py-3">{{ $leader->position }}</td>
                            <td class="px-4 py-3">{{ $leader->team->name ?? '—' }}</td>
                           <td class="px-4 py-3">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if ($leader->status === 'active') bg-green-600 text-white
                                @elseif ($leader->status === 'inactive') bg-gray-500 text-white
                                @elseif ($leader->status === 'retired') bg-yellow-500 text-white
                                @elseif ($leader->status === 'suspended') bg-gray-600 text-red-100
                                @else bg-gray-300 text-black @endif">
                                {{ ucfirst($leader->status) }}
                            </span>
                        </td>

                         <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.leaders.show', $leader) }}"
                                class="text-gray-600 hover:text-gray-800" title="View">
                                    <x-heroicon-s-eye class="w-5 h-5" />
                                </a>

                                <a href="{{ route('admin.leaders.edit', $leader) }}"
                                class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                </a>

                                <form action="{{ route('admin.leaders.destroy', $leader) }}" method="POST"
                                    onsubmit="return confirm('Delete this member?')">
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
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No Team members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaders->links() }}
        </div>
    </div>
</x-admin::layouts>
