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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8" style="width: 24px; height: 24px; color: inherit;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
            </a>

            <h3 class="text-2xl font-semibold text-gray-800">
                @if(isset($share))
                    Edit 
                    @if(strtolower($share->class) === 'first')
                        First Incentive
                    @elseif(strtolower($share->class) === 'original')
                        Original Shares
                    @else
                        {{ $share->class }}
                    @endif
                @else
                    Create Share Class
                @endif
            </h3>
        </div>

        {{-- Share Class Form --}}
        <form method="POST"
              action="{{ isset($share)
                          ? route('admin.shares.update', $share)
                          : route('admin.shares.store') }}">
            @csrf
            @if(isset($share))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Share Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Share Class</label>
                    <input
                        type="text"
                        name="class"
                        value="{{ old('class', $share->class ?? '') }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-100"
                        required
                    >
                </div>

                <!-- Origin/Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Origin/Source</label>
                    <input
                        type="text"
                        name="origin"
                        value="{{ old('origin', $share->origin ?? '') }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm"
                        placeholder="e.g. Legacy Co Ltd"
                    >
                </div>

                <!-- Total Units -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Units</label>
                    <input
                        type="number"
                        name="units"
                        value="{{ old('units', $share->units ?? '') }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm"
                        required
                    >
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                    name="description"
                    rows="3"
                    class="w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm"
                >{{ old('description', $share->description ?? '') }}</textarea>
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow transition"
                >
                    {{ isset($share) ? 'Update Share Class' : 'Create Share Class' }}
                </button>
            </div>
        </form>
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>
</x-admin::layouts>
