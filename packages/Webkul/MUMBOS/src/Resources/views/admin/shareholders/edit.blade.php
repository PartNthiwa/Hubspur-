<x-admin::layouts>
    <h2 class="text-lg font-semibold mb-4">Edit Shareholder</h2>

    <form action="{{ route('admin.shareholders.update', $shareholder) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

         @includeIf('mumbos::admin.shareholders.partials._form', [
            'shareholder' => $shareholder,
            'customers' => $customers ?? null
        ])

        <br>
        <p class="text-sm text-gray-500">Please fill in the details to add a new shareholder.</p>
        <br>

        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        <a href="{{ route('admin.shareholders.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
    </form>
</x-admin::layouts>
