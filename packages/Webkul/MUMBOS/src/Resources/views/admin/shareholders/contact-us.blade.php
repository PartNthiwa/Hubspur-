<x-admin::layouts>
    <x-slot:title>
        Contact Messages
    </x-slot:title>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Contact Messages</h1>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                
            </thead>
           <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($messages as $message)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $message->name }}</td>
                    <td class="px-6 py-4 text-sm text-blue-600">
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs whitespace-pre-line">
                        {{ \Illuminate\Support\Str::limit($message->message, 120) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $message->created_at->format('d M Y, H:i') }}
                    </td>
                  <td class="px-6 py-4 text-sm text-gray-700 space-x-2">
                    {{-- View --}}
                    <a href="{{ route('admin.shareholders.contact-us.show', $message->id) }}"
                    class="text-blue-500 hover:underline">View</a>

                    {{-- Reply --}}
                    <a href="mailto:{{ $message->email }}" class="text-green-500 hover:underline">Reply</a>

                    {{-- Delete --}}
                    <form action="{{ route('admin.shareholders.contact-us.destroy', $message->id) }}"
                        method="POST" class="inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        No contact messages found.
                    </td>
                </tr>
            @endforelse
        </tbody>

        </table>
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</x-admin::layouts>
