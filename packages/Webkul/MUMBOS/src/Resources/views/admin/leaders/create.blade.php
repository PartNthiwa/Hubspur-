<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4 px-6">
        <h2 class="text-lg font-semibold">Add Leadership Member</h2>
        <a href="{{ route('admin.leaders.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Go Back
        </a>
    </div>

    <form action="{{ route('admin.leaders.store') }}" method="POST" enctype="multipart/form-data" class="px-6 space-y-6">
        @csrf
        @include('mumbos::admin.leaders._form', ['leader' => null])
    </form>
</x-admin::layouts>
