<x-admin::layouts>
    <div class="mb-6">
        <a href="{{ route('admin.shareholders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Shareholders</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">
            Shareholder: 
            {{ $shareholder->customer?->first_name ?? 'N/A' }} 
            {{ $shareholder->customer?->last_name ?? '' }}
        </h2>

        <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
            <div><strong>Status:</strong> {{ $shareholder->is_active ? 'Active' : 'Inactive' }}</div>
            <div><strong>Shareholder #:</strong> {{ $shareholder->shareholder_number }}</div>
            <div><strong>ID Number:</strong> {{ $shareholder->id_number ?? '-' }}</div>
            <div><strong>Phone:</strong> {{ $shareholder->phone ?? '-' }}</div>
            <div><strong>Email:</strong> {{ $shareholder->email ?? '-' }}</div>
            <div><strong>Joined At:</strong> {{ $shareholder->joined_at ?? '-' }}</div>
            <div><strong>Board Member:</strong> {{ $shareholder->is_board_member ? 'Yes' : 'No' }}</div>
            <div><strong>Position:</strong> {{ $shareholder->position ?? '-' }}</div>
        </div>

        {{-- Shares Breakdown --}}
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Allocated Shares</h3>

            @if ($shareholder->shares->isNotEmpty())
                <table class="min-w-full bg-white rounded shadow text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="py-2 px-4">Share Class</th>
                            <th class="py-2 px-4">Units</th>
                            <th class="py-2 px-4">Price/Unit</th>
                            <th class="py-2 px-4">Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($shareholder->shares as $share)
                            @php 
                                $value = $share->pivot->units * $share->price_per_unit;
                                $total += $value;
                            @endphp
                            <tr>
                                <td class="py-2 px-4">{{ $share->class }}</td>
                                <td class="py-2 px-4">{{ $share->pivot->units }}</td>
                                <td class="py-2 px-4">KES {{ number_format($share->price_per_unit, 2) }}</td>
                                <td class="py-2 px-4">KES {{ number_format($value, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-semibold bg-gray-50">
                            <td colspan="3" class="py-2 px-4 text-right">Total Paid</td>
                            <td class="py-2 px-4">KES {{ number_format($total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-sm">No shares allocated.</p>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="mt-4 space-x-2">
            @if ($shareholder?->id)
                <a href="{{ route('admin.shareholders.edit', $shareholder) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
            @endif

            <form method="POST" action="{{ route('admin.shareholders.destroy', $shareholder->id) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Delete this shareholder?')"
                        class="text-red-600 hover:underline text-sm">Delete</button>
            </form>
        </div>
    </div>
</x-admin::layouts>
