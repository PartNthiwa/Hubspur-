<x-admin::layouts>

    <div class="flex items-center justify-between mb-6 mt-8">
        <h2 class="text-2xl font-semibold text-gray-900">Add Membership Type</h2>
        <a href="{{ route('admin.membership-types.index') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition text-sm">
            <x-heroicon-s-arrow-left class="w-4 h-4" />
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.membership-types.store') }}">
            @csrf

            {{-- Global error display --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p class="font-semibold">Please correct the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-6">
                {{-- Membership Name --}}
                <div>
                    <label for="type" class="block text-base font-semibold text-gray-800 mb-2">Membership Name</label>
                    <input type="text" name="type" id="type"
                           value="{{ old('type') }}" placeholder="Type of Membership"
                           class="w-full px-4 py-2 border @error('type') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none"
                           required>
                    @error('type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Membership Amount --}}
                <div>
                    <label for="share_value" class="block text-base font-semibold text-gray-800 mb-2">Membership Amount (KES)</label>
                    <input type="number" name="share_value" id="share_value" step="0.01"
                           value="{{ old('share_value') }}" placeholder="Membership Amount"
                           class="w-full px-4 py-2 border @error('share_value') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none"
                           required>
                    @error('share_value') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Visibility --}}
                <div>
                    <label for="visibility" class="block text-base font-semibold text-gray-800 mb-2">Visibility</label>
                    <select name="visibility" id="visibility"
                            class="w-full px-4 py-2 border @error('visibility') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none"
                            required>
                        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                    @error('visibility') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Active Toggle --}}
                <div class="flex items-center mt-8">
                    <input type="hidden" name="is_active" value="0">
                    <label for="is_active" class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-300"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <span class="ml-3 text-sm text-gray-700">Active</span>
                    </label>
                    @error('is_active') <p class="text-red-600 text-xs mt-1 ml-4">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-6">
                <label for="description" class="block text-base font-semibold text-gray-800 mb-2">Description</label>
                <textarea name="description" id="description" rows="5"
                          placeholder="This description will be available on the user side"
                          class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="mt-8 text-right">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                    <x-heroicon-s-check class="w-5 h-5" />
                    Save Membership
                </button>
            </div>

            {{-- Footer Note --}}
            <div class="mt-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
            </div>
        </form>
    </div>

</x-admin::layouts>
