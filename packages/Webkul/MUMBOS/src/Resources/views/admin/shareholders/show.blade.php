<x-admin::layouts>
    <div class="mb-6">
        <a href="{{ route('admin.shareholders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Shareholders</a>
    </div>
{{ $shareholder->customer->first_name }}

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
            <div><strong>Share Class:</strong> {{ $shareholder->share_class ?? '-' }}</div>
            <div><strong>Units:</strong> {{ $shareholder->share_units }}</div>
            <div><strong>Capital Paid:</strong> KES {{ number_format($shareholder->capital_paid, 2) }}</div>
            <div><strong>Joined At:</strong> {{ $shareholder->joined_at ?? '-' }}</div>
            <div><strong>Board Member:</strong> {{ $shareholder->is_board_member ? 'Yes' : 'No' }}</div>
            <div><strong>Position:</strong> {{ $shareholder->position ?? '-' }}</div>
        </div>

        <div class="mt-4 space-x-2">
           @if($shareholder?->id)
                <a href="{{ route('admin.shareholders.edit', $shareholder) }}"
                class="text-blue-600 hover:underline text-sm">Edit</a>
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
