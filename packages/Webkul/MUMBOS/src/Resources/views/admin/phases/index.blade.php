<x-admin::layouts>
    {{-- Header --}}
    <div class="bg-white mt-8 mb-6 flex items-center justify-between px-6">
        <h1 class="text-2xl font-semibold text-gray-900">Phases</h1>
        <a href="{{ route('admin.phases.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            Add New Phase
        </a>
    </div>

    {{-- Table --}}
    <div class="w-full bg-white rounded-2xl shadow-lg overflow-x-auto px-6">
        <table class="w-full min-w-full text-sm text-gray-800 border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-600 text-white">
                <tr>
                    <th class="px-6 py-3 border text-left">#</th>
                    <th class="px-6 py-3 border text-left">Name</th>
                    <th class="px-6 py-3 border text-left">Value (KES/share)</th>
                    <th class="px-6 py-3 border text-left">Start</th>
                    <th class="px-6 py-3 border text-left">End</th>
                    <th class="px-6 py-3 border text-left">Description</th>
                    <th class="px-6 py-3 border text-left">Status</th>
                    <th class="px-6 py-3 border text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($phases as $phase)
                    <tr class="hover:bg-gray-100 hover:shadow-sm transition">
                        <td class="px-6 py-4 border">{{ $phase->id }}</td>
                        <td class="px-6 py-4 border font-medium">{{ $phase->name }}</td>
                        <td class="px-6 py-4 border">{{ number_format($phase->share_value, 2) }}</td>
                        <td class="px-6 py-4 border">{{ optional($phase->starts_at)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-6 py-4 border">{{ optional($phase->ends_at)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-6 py-4 border">{{ $phase->description }}</td>
                        <td class="px-6 py-4 border">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded 
                                {{ $phase->trashed() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $phase->trashed() ? 'Archived' : 'Active' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border">
                            <div class="flex items-center gap-4">
                                @if ($phase->trashed())
                                    <button
                                        onclick="openRestoreModal('{{ route('admin.contributions.phases.restore.custom', $phase->id) }}', '{{ addslashes($phase->name) }}')"
                                        class="text-green-600 hover:text-green-800"
                                        title="Restore"
                                    >
                                        <x-heroicon-s-arrow-path class="w-5 h-5" />
                                    </button>
                                    <button
                                        onclick="openForceDeleteModal('{{ route('admin.contributions.phases.force-delete.custom', $phase->id) }}', '{{ addslashes($phase->name) }}')"
                                        class="text-red-700 hover:text-red-900"
                                        title="Force Delete"
                                    >
                                        <x-heroicon-s-trash class="w-5 h-5" />
                                    </button>
                                @else
                                    <a href="{{ route('admin.phases.edit', $phase) }}"
                                       class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.phases.destroy', $phase->id) }}"
                                          onsubmit="return confirm('Delete this phase?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <x-heroicon-s-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 border">No phases found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 mt-6">
        {{ $phases->links() }}
    </div>

    {{-- Footer --}}
    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>

    {{-- Restore Modal --}}
    <div id="restoreModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-md shadow-md p-4 w-[600px] text-sm">
            <h2 class="text-green-700 text-base font-semibold mb-2">Restore Phase</h2>
            <p class="text-xs mb-4">Restore <span id="restorePhaseName" class="font-semibold text-green-700"></span>?</p>
            <form id="restoreForm" method="POST">
                @csrf
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('restoreModal')" class="text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 text-xs rounded">Restore</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Force Delete Modal --}}
    <div id="forceDeleteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-md shadow-md p-4 w-[600px] text-sm">
            <h2 class="text-red-600 text-base font-semibold mb-2">Permanently Delete Phase</h2>
            <p class="text-xs mb-4">Permanently delete <span id="forceDeletePhaseName" class="font-semibold text-red-700"></span> Contribution?</p>
            <form id="forceDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('forceDeleteModal')" class="text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                   <button type="submit"
                        style="
                            background-color: #dc2626; /* red-600 */
                            color: white;
                            padding: 0.25rem 0.75rem; /* py-1 px-3 */
                            font-size: 0.75rem; /* text-xs */
                            border-radius: 0.25rem; /* rounded */
                            border: none;
                            cursor: pointer;
                            transition: background-color 0.2s ease-in-out;
                        "
                        onmouseover="this.style.backgroundColor='#b91c1c'"  {{-- red-700 --}}
                        onmouseout="this.style.backgroundColor='#dc2626'"
                    >
                        Delete
                    </button>

                </div>
            </form>
        </div>
    </div>

    {{-- Modal Script --}}
    <script>
        function openRestoreModal(actionUrl, phaseName) {
            const modal = document.getElementById('restoreModal');
            const form = document.getElementById('restoreForm');
            const nameSpan = document.getElementById('restorePhaseName');

            form.action = actionUrl;
            nameSpan.textContent = phaseName;
            modal.classList.remove('hidden');
        }

        function openForceDeleteModal(actionUrl, phaseName) {
            const modal = document.getElementById('forceDeleteModal');
            const form = document.getElementById('forceDeleteForm');
            const nameSpan = document.getElementById('forceDeletePhaseName');

            form.action = actionUrl;
            nameSpan.textContent = phaseName;
            modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === "Escape") {
                closeModal('restoreModal');
                closeModal('forceDeleteModal');
            }
        });
    </script>
</x-admin::layouts>
