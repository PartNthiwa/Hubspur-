<x-admin::layouts>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Membership Types</h2>
        <a href="{{ route('admin.membership-types.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">+ New Type</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto text-sm text-gray-800">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($membershipTypes as $type)
                    <tr>
                        <td class="px-4 py-2">{{ $type->type }}</td>
                        <td class="px-4 py-2">KES {{ number_format($type->share_value) }}</td>
                        <td class="px-4 py-2 max-w-xs truncate" title="{{ $type->description }}">
                            {{ \Illuminate\Support\Str::limit($type->description, 40) }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="text-xs px-2 py-1 rounded {{ $type->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $type->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.membership-types.edit', $type) }}"
                               class="text-blue-600 hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.membership-types.destroy', $type) }}" method="POST"
                                  onsubmit="return confirm('Delete this type?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin::layouts>
