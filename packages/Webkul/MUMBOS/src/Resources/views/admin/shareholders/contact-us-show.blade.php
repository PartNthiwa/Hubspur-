<x-admin::layouts>
    <x-slot:title>
        View Contact Message
    </x-slot:title>

    <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-semibold mb-4 text-gray-800">Message Details</h1>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name:</label>
            <p class="text-gray-900">{{ $message->name }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email:</label>
            <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline">{{ $message->email }}</a>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Message:</label>
            <p class="text-gray-800 whitespace-pre-line">{{ $message->message }}</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Submitted At:</label>
            <p class="text-gray-500">{{ $message->created_at->format('d M Y, H:i') }}</p>
        </div>

        <div class="flex gap-4">
            <a href="mailto:{{ $message->email }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Reply
            </a>

            <form action="{{ route('admin.shareholders.contact-us.destroy', $message->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Delete
                </button>
            </form>

            <a href="{{ route('admin.shareholders.contact-us') }}" class="text-sm text-gray-600 hover:underline mt-2">
                Back to Messages
            </a>
        </div>
    </div>
</x-admin::layouts>
