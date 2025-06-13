<x-admin::layouts>
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Share Details</h1>

    <div class="bg-white p-4 shadow rounded">
        <p><strong>Class:</strong> {{ $share->class }}</p>
        <p><strong>Units:</strong> {{ $share->units }}</p>
        <p><strong>Price/Unit:</strong> Ksh {{ number_format($share->price_per_unit, 2) }}</p>
        <p><strong>Total Value:</strong> Ksh {{ number_format($share->total_value, 2) }}</p>
    </div>
</x-admin::layouts>
