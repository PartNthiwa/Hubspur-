<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4 px-6">
        <h2 class="text-lg font-semibold">Add New Team</h2>
        <a href="{{ route('admin.teams.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Go Back
        </a>
    </div>

   <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">

        @csrf
        @include('mumbos::admin.teams._form', ['team' => null])
    </form>
</x-admin::layouts>
