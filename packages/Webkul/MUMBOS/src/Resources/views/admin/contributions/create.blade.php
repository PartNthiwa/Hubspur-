@php
    $isEdit = isset($contribution);
    $selectedStatus = old('status', $contribution->status ?? 'pending');
    $selectedPaymentStatus = old('payment_status', $contribution->payment_status ?? 'pending');
    $selectedPaymentMethod = old('payment_method', $contribution->payment_method ?? 'bank_transfer');
@endphp

<x-admin::layouts>
    <div class="flex items-center justify-between mb-4 mt-4">
        <h2 class="text-lg font-semibold">Add New Contribution</h2>
        <a href="{{ route('admin.contributions.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm transition">
            ← Back to List
        </a>
    </div>

    <div id="contrib-form">
        <form action="{{ route('admin.contributions.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6 bg-white p-6 rounded shadow"
        >
            @csrf

            @include('mumbos::admin.contributions.partials._form', [
             'contribution' => new \Webkul\MUMBOS\Models\Contribution, 
                'shareholders' => $shareholders ?? [],
                'phases'       => $phases ?? [],
            ])

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Contribution
                </button>
                <a href="{{ route('admin.contributions.index') }}"
                   class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>


    </div>
    

        </form>

  


    </div>

  
   

</x-admin::layouts>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('payment_method');
    if (!select) return; // Prevent the error

    const fields = document.querySelectorAll('.gateway-fields');

    function toggleFields() {
        const selectedMethod = select.value.trim();
        fields.forEach(field => {
            const method = field.getAttribute('data-method').trim();
            field.style.display = (method === selectedMethod) ? 'block' : 'none';
        });
    }

    toggleFields();
    select.addEventListener('change', toggleFields);
});

</script>


