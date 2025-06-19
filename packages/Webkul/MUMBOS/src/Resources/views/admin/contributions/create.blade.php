<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Add New Contribution</h2>
        <a href="{{ route('admin.contributions.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to List
        </a>
    </div>

    <form action="{{ route('admin.contributions.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6 bg-white p-6 rounded shadow"
    >
        @csrf

        {{-- All the standard fields: shareholder, amount, method, date, status, notes, etc. --}}
        @include('mumbos::admin.contributions.partials._form', [
            'contribution' => null,
            'shareholders' => $shareholders,
        ])

        {{-- Receipt upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Payment Receipt</label>
            <input type="file"
                   name="payment_receipt"
                   accept=".jpg,.jpeg,.png,.pdf"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            <p class="text-xs text-gray-500 mt-1">
                (Optional) Upload a payment receipt file (JPG, PNG or PDF).
            </p>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Contribution
            </button>
            <a href="{{ route('admin.contributions.index') }}"
               class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</x-admin::layouts>
