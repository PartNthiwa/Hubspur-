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

    <hr class="my-6">

    <h3 class="text-md font-semibold mb-2">Allocate Shares</h3>

    <!-- <form action="{{ route('admin.shareholders.allocate-shares', $shareholder) }}" method="POST" class="space-y-3">
        @csrf
        @method('POST')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="share_id" class="block text-sm font-medium text-gray-700">Share Class</label>
                <select name="share_id" id="share_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    @foreach ($shares as $share)
                        <option value="{{ $share->id }}">{{ $share->class }} (KES {{ number_format($share->price_per_unit, 2) }} per unit)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="units" class="block text-sm font-medium text-gray-700">Units</label>
                <input type="number" name="units" id="units" class="w-full border border-gray-300 rounded px-3 py-2" required min="1">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Allocate Shares</button>
    </form> -->

</x-admin::layouts>
