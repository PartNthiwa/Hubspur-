<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Add New Contribution</h2>
        <a href="{{ route('admin.contributions.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to List
        </a>
    </div>

    <form action="{{ route('admin.contributions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        @include('mumbos::admin.contributions.partials._form', [
            'contribution' => null,
            'shareholders' => $shareholders,
        ])

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Contribution
            </button>
            <a href="{{ route('admin.contributions.index') }}"
               class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</x-admin::layouts>
