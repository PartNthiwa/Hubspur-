
<x-admin::layouts>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Members List</h1>
        <a href="{{ route('admin.shareholders.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded shadow-sm transition">
            + Add Member
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Member Number</th>
                    <th class="px-4 py-3 text-left font-semibold">Full Name</th>
                    <th class="px-4 py-3 text-left font-semibold">Share Class</th>
                    <th class="px-4 py-3 text-left font-semibold">Share Units</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Contributions</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($shareholders as $shareholder)
                    @php
                        $totalPaid = 0;
                        $shareClasses = [];
                        foreach ($shareholder->shares as $share) {
                            $units = $share->pivot->units ?? 0;
                            $price = $share->price_per_unit ?? 0;
                            $totalPaid += $units * $price;
                            $shareClasses[] = $share->class;
                        }
                    @endphp

                    <tr>
                        <td class="px-4 py-3">{{ $shareholder->id }}</td>
                        <td class="px-4 py-3">{{ $shareholder->shareholder_number }}</td>
                        <td class="px-4 py-3">
                            {{ $shareholder->customer->first_name }} {{ $shareholder->customer->last_name }}
                        </td>
                        <td class="px-4 py-3">
                            @forelse ($shareholder->shares as $share)
                                @php
                                    $colors = [
                                        'bg-blue-100 text-blue-900',
                                        'bg-green-100 text-green-900',
                                        'bg-yellow-100 text-yellow-900',
                                        'bg-purple-100 text-purple-900',
                                        'bg-pink-100 text-pink-900',
                                        'bg-red-100 text-red-900',
                                        'bg-indigo-100 text-indigo-900',
                                        'bg-teal-100 text-teal-900',
                                        'bg-orange-100 text-orange-900',
                                    ];
                                    $randomColor = $colors[array_rand($colors)];
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $randomColor }}">
                                    {{ $share->class }}
                                </span>
                            @empty
                                <span class="text-gray-500">-</span>
                            @endforelse
                        </td>

                 <td class="px-4 py-3">
                    @forelse($shareholder->shares as $share)
                        <div class="mb-2">
                           <form action="{{ route('admin.shareholders.update-units', [$shareholder->id, $share->id]) }}"  class="flex items-center gap-2" method="POST">

                               
                                @csrf
                                @method('PUT')

                                <label class="text-xs font-medium text-gray-700">{{ $share->class }}:</label>
                                <input type="number"
                                    name="units"
                                    value="{{ $share->pivot->units }}"
                                    min="0"
                                    class="w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                    required>

                                <button type="submit"
                                        class="text-blue-600 hover:text-blue-800 text-xs underline">
                                    Update
                                </button>
                            </form>
                        </div>
                    @empty
                        <span class="text-gray-500">No shares</span>
                    @endforelse
                </td>


                        <td class="px-4 py-3">{{ $shareholder->capital_paid }}</td>
                       <td class="px-4 py-3">
                            @if ($shareholder->is_active)
                                <span style="background-color: #16a34a;" class="text-white text-xs px-2 py-1 rounded">
                                    Active
                                </span>
                            @else
                                <span style="background-color: #dc2626;" class="text-white text-xs px-2 py-1 rounded">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-x-4">
                                <a href="{{ route('admin.shareholders.show', $shareholder) }}"
                                   class="text-indigo-600 hover:text-indigo-800" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('admin.shareholders.edit', $shareholder) }}"
                                   class="text-blue-600 hover:text-blue-800a" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-7.414a2 2 0 112.828 2.828L11 19l-4 1 1-4 9.586-9.586z"/>
                                    </svg>
                                </a>

<button
    type="button"
    onclick="openModal('{{ $shareholder->id }}')"
    class="text-green-600 hover:text-green-800"
    title="Allocate Shares"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
</button>

                                                            <form method="POST" action="{{ route('admin.shareholders.destroy', $shareholder->id) }}"
                                      class="inline-block" onsubmit="return confirm('Delete this shareholder?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No shareholders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $shareholders->links() }}
    </div>
    
 @foreach ($shareholders as $shareholder)
    <div
        id="allocateModal-{{ $shareholder->id }}"
        class="fixed inset-0 z-50 hidden bg-white bg-opacity-40 flex items-center justify-center transition-opacity duration-300"
    >
        <div class="bg-blue-100 border border-gray-300 rounded-md w-[90%] sm:w-[500px] shadow-2xl p-6 relative border border-gray-200">
         <button
                onclick="closeModal('{{ $shareholder->id }}')"
                class="absolute top-3 left-3 z-10 text-gray-400 hover:text-gray-600 text-2xl leading-none"
                aria-label="Close"
            >
                &times;
            </button>

            <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2 pt-2 pl-4 pr-6">
                Allocate Shares to {{ $shareholder->customer->first_name }}
            </h2>

            <form action="{{ route('admin.shareholders.allocate-shares', $shareholder) }}" method="POST" class="space-y-5 ">
                @csrf

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-6 ">
                    <div>
                        <label for="share_id_{{ $shareholder->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                            Share Class
                        </label>
                        <select
                            name="share_id"
                            id="share_id_{{ $shareholder->id }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200"
                            required
                        >
                            @foreach ($shares as $share)
                                <option value="{{ $share->id }}">
                                    {{ $share->class }} (KES {{ number_format($share->price_per_unit, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="units_{{ $shareholder->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                            Units
                        </label>
                        <input
                            type="number"
                            name="units"
                            id="units_{{ $shareholder->id }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200"
                            required
                            min="1"
                            placeholder="e.g. 10"
                        >
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm font-medium transition"
                    >
                        Allocate
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach


<script>
    function openModal(id) {
        document.getElementById('allocateModal-' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('allocateModal-' + id).classList.add('hidden');
    }
</script>

</x-admin::layouts>
