<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Add New Shareholder</h2>

        <a href="{{ route('admin.shareholders.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Go Back
        </a>
    </div>

    <form action="{{ route('admin.shareholders.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('mumbos::admin.shareholders.partials._form', ['shareholder' => $shareholder ?? null, 'customers' => $customers])

        <p class="text-sm text-gray-500 mt-4 mb-2">Please fill in the details to add a new shareholder.</p>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save
            </button>

            <a href="{{ route('admin.shareholders.index') }}"
               class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</x-admin::layouts>
