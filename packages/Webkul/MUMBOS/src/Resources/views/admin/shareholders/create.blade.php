<x-admin::layouts>
    <h2 class="text-lg font-semibold mb-4">Add New Shareholder</h2>

    <form action="{{ route('admin.shareholders.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('mumbos::admin.shareholders.partials._form', ['shareholder' => $shareholder ?? null, 'customers' => $customers])
        <br>
        <p class="text-sm text-gray-500">Please fill in the details to add a new shareholder.</p>
        <br>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        <a href="{{ route('admin.shareholders.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
    </form>
</x-admin::layouts>
