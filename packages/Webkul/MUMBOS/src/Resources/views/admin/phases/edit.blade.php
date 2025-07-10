<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-8">
        <h2 class="text-2xl font-semibold">Edit Phase: {{ $phase->name }}</h2>
      <a href="{{ route('admin.phases.index') }}"
   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition">
    <x-heroicon-s-arrow-left class="w-5 h-5" />
    Back to Phases
</a>

    </div>
<div class="flex gap-4 mb-8 px-2">
    <div class=" bg-green-100 border-l-4 border-red-600 text-yellow-800 p-4 rounded shadow-sm">
        <p class="text-sm">
            <strong>Note:</strong> <span style="color:red">CAREFUL This is a Global Change.</span>
        </p>
    </div>
</div>

    <div class="bg-white rounded-lg  p-6">
     <form method="POST" action="{{ route('admin.phases.update', $phase) }}">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="mb-4 rounded border-l-4 border-red-500 bg-red-100 p-4 text-sm text-red-700">
            <strong>There were some issues with your submission:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name"
               class="mt-1 block w-full border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md px-2 py-2 shadow-sm"
               value="{{ old('name', $phase->name) }}" required>
        @error('name')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>


    <div class="mb-4">
        <label for="share_value" class="block text-sm font-medium text-gray-700">
            Value per Share (KES)
        </label>
        <input type="number" name="share_value" id="share_value" step="0.01"
               class="mt-1 block w-full border @error('share_value') border-red-500 @else border-gray-300 @enderror rounded-md px-2 py-2 shadow-sm"
               value="{{ old('share_value', $phase->share_value) }}" required>
        @error('share_value')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">
            Description
        </label>
        <textarea name="description" id="description" rows="3"
                  class="mt-1 block w-full border @error('description') border-red-500 @else border-gray-300 @enderror rounded-md px-2 py-2 shadow-sm"
        >{{ old('description', $phase->description) }}</textarea>
        @error('description')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
        Update Phase
    </button>
</form>

    </div>


       {{-- Footer --}}
    <div class="mt-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>
</x-admin::layouts>
