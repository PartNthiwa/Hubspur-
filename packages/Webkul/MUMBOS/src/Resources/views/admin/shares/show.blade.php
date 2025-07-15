<x-admin::layouts>
    {{-- Header Note and Back Button --}}
    <div class="mb-2 mt-8 flex items-center justify-between px-6">
        <p class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded shadow-sm">
            <strong>Note:</strong> Manage share allocations per shareholder for the class <strong>“{{ $share->class }}”</strong>.
        </p>

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


    <div
        class="w-full bg-white shadow-lg rounded-2xl overflow-hidden"
        x-data="{
            search: '',
            matches(row) {
                let term = this.search.toLowerCase();
                let name = row.dataset.name?.toLowerCase() || '';
                return name.includes(term) || term === '';
            },
            highlight(text, term) {
                if (!term) return text;
                const regex = new RegExp('(' + term.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&') + ')', 'gi');
                return text.replace(regex, '<mark class=\'bg-yellow-200 text-yellow-900\' style=\'padding:0 2px;border-radius:2px;\'>$1</mark>');
            }
        }"
    >
        {{-- Search Input --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <input
                type="text"
                x-model.debounce.300="search"
                placeholder="Search shareholders by name..."
                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            />
              <a href="{{ route('admin.shares.allocate-form', $share->id) }}"
       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition">
        <x-heroicon-s-plus class="w-4 h-4" />
        Allocate to Another Shareholder
    </a>
        </div>

        {{-- Allocation Table --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 border">#</th>
                        <th class="px-6 py-3 border text-left">Shareholder</th>
                        <th class="px-6 py-3 border text-left">Units</th>
                        <th class="px-6 py-3 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-black divide-y divide-gray-200">
                    @forelse($share->shareholders as $holder)
             @php
    $customer = optional($holder->customer);
    $fullName = $holder->full_name
        ?? ($customer->first_name && $customer->last_name
            ? "{$customer->first_name} {$customer->last_name}"
            : 'Unknown Shareholder');
@endphp


                        <tr
                            x-show="matches($el)"
                            data-name="{{ strtolower(trim($fullName)) }}"
                            class="hover:bg-gray-50 transition"
                        >
                            <td class="px-6 py-4 border">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border">
                      <span x-html="highlight({{ json_encode($fullName) }}, search)"></span>

                            </td>

                            {{-- Display Units --}}
                            <td class="px-6 py-4 border text-right">
                                {{ $holder->pivot->units }}
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 border flex gap-2">
                               <button
    onclick="openEditModal('{{ route('admin.shares.update-allocation', [$share->id, $holder->shareholder_number]) }}', '{{ $holder->pivot->units }}', '{{ addslashes($fullName) }}')"
    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 text-xs rounded-md">
    Edit
</button>

                             
<button
    onclick="openRemoveModal('{{ route('admin.shares.update-allocation', [$share->id, $holder->shareholder_number]) }}', '{{ addslashes($fullName) }}')"
    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-xs rounded-md">
    Remove
</button>
                            </td>
                            
                        </tr>
                    @empty
                     <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-700">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-red-600 font-medium">No allocations yet.</span>
                                
                                <a href="{{ route('admin.shares.allocate-form', $share->id) }}"
                                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition">
                                    <x-heroicon-s-plus class="w-4 h-4" />
                                    Allocate Shares
                                </a>
                            </div>
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-md shadow-md p-3 w-[440px] text-sm">
        <h2 class="text-sm font-semibold mb-2">
            Edit Units for <span id="editModalName" class="text-blue-600"></span>
        </h2>
        <form id="editAllocationForm" method="POST">
            @csrf
            @method('PUT')
            <input type="number" name="units" id="editUnitsInput" min="0"
                   class="w-full border border-gray-300 rounded px-2 py-1 mb-2 text-xs focus:outline-none focus:ring focus:ring-blue-200" />
            <div class="flex justify-end gap-1">
                <button type="button" onclick="closeModal('editModal')" class="px-2 py-1 text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 text-xs rounded">Save</button>
            </div>
        </form>
    </div>
</div>
<div id="removeModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-md shadow-md p-3 w-[440px] text-sm">
        <h2 class="text-sm font-semibold text-red-600 mb-2">Confirm</h2>
        <p class="text-xs mb-3">Remove all units for <span id="removeModalName" class="font-semibold text-red-700"></span>?</p>
        <form id="removeAllocationForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="units" value="0" />
            <div class="flex justify-end gap-1">
                <button type="button" onclick="closeModal('removeModal')" class="px-2 py-1 text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                <button
    type="submit"
    style="
        background-color: #dc2626; /* red-600 */
        color: white;
        padding: 0.25rem 0.5rem; /* py-1 px-2 */
        font-size: 0.75rem; /* text-xs */
        border-radius: 0.25rem; /* rounded */
        border: none;
        cursor: pointer;
    "
    onmouseover="this.style.backgroundColor='#b91c1c'"
    onmouseout="this.style.backgroundColor='#dc2626'"
>
    Remove
</button>

            </div>
        </form>
    </div>
</div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function openEditModal(actionUrl, currentUnits, shareholderName) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editAllocationForm');
        const input = document.getElementById('editUnitsInput');
        const nameSpan = document.getElementById('editModalName');

        form.action = actionUrl;
        input.value = currentUnits;
        nameSpan.textContent = shareholderName;

        modal.classList.remove('hidden');
    }

    function openRemoveModal(actionUrl, shareholderName) {
        const modal = document.getElementById('removeModal');
        const form = document.getElementById('removeAllocationForm');
        const nameSpan = document.getElementById('removeModalName');

        form.action = actionUrl;
        nameSpan.textContent = shareholderName;

        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // ESC key closes modals
    document.addEventListener('keydown', function (e) {
        if (e.key === "Escape") {
            closeModal('editModal');
            closeModal('removeModal');
        }
    });
</script>

</x-admin::layouts>
