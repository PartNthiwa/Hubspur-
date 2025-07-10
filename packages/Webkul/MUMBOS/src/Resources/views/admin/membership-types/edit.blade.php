<x-admin::layouts>

    <div class="bg-white mt-8  mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Membership Type</h1>
 <a href="{{ route('admin.membership-types.index') }}"
   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-600 text-white font-medium px-4 py-2 rounded-lg shadow transition"
>
    <x-heroicon-s-arrow-left class="w-5 h-5 text-white" />
    Back to List
</a>




    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg -mt-6 p-8">
       <form method="POST" action="{{ route('admin.membership-types.update', $membershipType->id) }}">
    @csrf
    @method('PUT')


    <div class="grid grid-cols-2 gap-6">
  
        <div>
            <label for="type" class="block text-base font-semibold text-gray-800 mb-2">Type Name</label>
            <input
                type="text"
                name="type"
                id="type"
                value="{{ old('type', $membershipType->type) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                required
            >
            @error('type')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

    
        <div>
            <label for="share_value"class="block text-base font-semibold text-gray-800 mb-2">Amount (KES)</label>
            <input
                type="number"
                name="share_value"
                id="share_value"
                step="0.01"
                value="{{ old('share_value', $membershipType->share_value) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                required
            >
            @error('share_value')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

 
        <div>
            <label for="visibility" class="block text-base font-semibold text-gray-800 mb-2">Visibility</label>
            <select
                name="visibility"
                id="visibility"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                required
            >
                <option value="public" {{ old('visibility', $membershipType->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                <option value="private" {{ old('visibility', $membershipType->visibility) === 'private' ? 'selected' : '' }}>Private</option>
            </select>
            @error('visibility')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

   
        <div class="flex items-center mt-6">
            <input type="hidden" name="is_active" value="0">
            <label for="is_active" class="inline-flex items-center cursor-pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-300"
                    {{ old('is_active', $membershipType->is_active) ? 'checked' : '' }}
                />
                <span class="ml-3 text-sm text-gray-700">Active</span>
            </label>
            @error('is_active')
                <p class="text-red-600 text-xs mt-1 ml-4">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6">
        <label for="description" class="block text-base font-semibold text-gray-800 mb-2">Description</label>
        <textarea
            name="description"
            id="description"
            rows="15"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-transparent"
        >{{ old('description', $membershipType->description) }}</textarea>
        @error('description')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-6 text-right ">
        <button
            type="submit"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition"
        >
            <x-heroicon-s-check class="w-5 h-5" />
            Update Membership
        </button>
    </div>
</form>

    </div>
    <div class="mt-4 text-center text-sm text-gray-500">
    &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
</div>


</x-admin::layouts>
