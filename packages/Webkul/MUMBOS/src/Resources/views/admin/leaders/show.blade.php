<x-admin::layouts>
    <div class="flex items-center justify-between mb-6 mt-6 px-6">
        <h2 class="text-lg font-semibold"> Member Details</h2>
        <a href="{{ route('admin.leaders.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to Members
        </a>
    </div>

    <div class="px-6">
        <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row gap-6">
            {{-- Profile Photo --}}
            <div class="flex-shrink-0 w-full md:w-1/4">
                @if($leader->photo_url)
                    <img src="{{ $leader->photo_url }}" alt="{{ $leader->full_name }}"
                         class="rounded-lg shadow-md w-full object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 rounded flex items-center justify-center text-gray-500">
                        No photo
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 space-y-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $leader->full_name }}</h3>
                    <p class="text-sm text-gray-600">{{ $leader->position }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Team:</p>
                    <p class="text-base text-gray-800 font-medium">
                        {{ $leader->team->name ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status:</p>
                    <span class="inline-block text-sm px-2 py-1 rounded 
                        {{ $leader->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                        {{ $leader->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Short Bio:</p>
                    <div class="prose max-w-none text-gray-700 text-sm">
                        {!! nl2br(e($leader->bio ?? '—')) !!}
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.leaders.edit', $leader) }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md">
                        <x-heroicon-s-pencil class="w-5 h-5 mr-1" />
                        Edit
                    </a>
                    <form action="{{ route('admin.leaders.destroy', $leader) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this member?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-md">
                            <x-heroicon-s-trash class="w-5 h-5 mr-1" />
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
