<x-admin::layouts>
    <div class="mt-8 px-4">
        {{-- Header with Back Button --}}
        <div class="flex items-center justify-between mb-6">
            <a
                href="{{ route('admin.shares.index') }}"
                title="Back"
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 48px;
                    height: 48px;
                    color: #dc2626;
                    border-radius: 9999px;
                    transition: transform 0.2s ease-in-out;
                "
                onmouseover="this.style.transform='translateX(-4px)'"
                onmouseout="this.style.transform='translateX(0)'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.8" style="width: 24px; height: 24px; color: inherit;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
            </a>

            <h3 class="text-2xl font-semibold text-gray-800">
                Allocate Units of “{{ $share->class }}”
            </h3>
        </div>
@if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        {{-- Allocation Form --}}
        <form method="POST" action="{{ route('admin.shares.allocate', $share) }}" class="space-y-6">
            @csrf

     
           
            <div>
                <label class="block text-sm font-medium text-gray-700">Shareholder Name</label>
                <select
                    name="shareholder_id"
                    class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-100"
                    required
                >
                    <option value="">-- Choose shareholder --</option>
                    @foreach($shareholders as $holder)
                        <option value="{{ $holder->id }}"
                            {{ old('shareholder_id') == $holder->id ? 'selected' : '' }}>
                            {{ $holder->first_name }} ({{ $holder->shareholder_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Units Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mt-5">Units to Assign</label>
                <input
                    type="number"
                    name="units"
                    min="1"
                    max="{{ $share->units }}"
                    value="{{ old('units') ?? 1 }}"
                    class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm"
                    required
                >
                <p class="text-xs text-gray-500 mt-5 mb-5">
                    Total units available for Allocation: <strong style="color:red;">{{ $share->units - $share->shareholders()->sum('shareholder_share.units') }}</strong>
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow transition"
                >
                    Allocate Units
                </button>
            </div>
        </form>

        {{-- Footer --}}
        <div class="mt-10 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
        </div>
    </div>
</x-admin::layouts>
