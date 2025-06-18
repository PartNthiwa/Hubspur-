<x-admin::layouts>
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Share Details</h1>

    <div class="bg-white p-6 shadow rounded space-y-2 text-sm">
        <p><strong>Class:</strong> {{ $share->class }}</p>
        <p><strong>Total Units:</strong> {{ $share->units }}</p>
        <p><strong>Available Units:</strong> {{ $share->available_units }}</p>
        <p><strong>Price per Unit:</strong> Ksh {{ number_format($share->price_per_unit, 2) }}</p>
        <p><strong>Total Value:</strong> Ksh {{ number_format($share->total_value, 2) }}</p>
        <p><strong>Status:</strong>
            <span class="inline-block px-2 py-1 rounded {{ $share->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                {{ $share->is_active ? 'Active' : 'Inactive' }}
            </span>
        </p>
        <p><strong>Visibility:</strong> {{ ucfirst($share->visibility) }}</p>
        <p><strong>Description:</strong> {{ $share->description }}</p>
        @if ($share->icon_url)
            <div class="mt-2">
                <strong>Icon:</strong><br>
                <img src="{{ asset('storage/' . $share->icon_url) }}" alt="Icon" class="h-20 w-20 object-cover rounded">
            </div>
        @endif
    </div>
</x-admin::layouts>
