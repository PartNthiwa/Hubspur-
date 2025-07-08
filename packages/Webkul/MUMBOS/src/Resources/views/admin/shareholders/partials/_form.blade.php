<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow-md">

        {{-- First Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
            <input type="text" name="first_name" value="{{ old('first_name', optional($shareholder)->first_name) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            @error('first_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Last Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
            <input type="text" name="last_name" value="{{ old('last_name', optional($shareholder)->last_name) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            @error('last_name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', optional($shareholder)->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', optional($shareholder)->phone) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            @error('phone')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Member Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Member Number <span class="text-red-500">*</span></label>
            <input type="text"
                   name="shareholder_number"
                   value="{{ old('shareholder_number', optional($shareholder)->shareholder_number) }}"
                   readonly
                   placeholder="auto-generated"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed focus:ring-0 focus:outline-none" />
        </div>

        {{-- ID Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
            <input type="text" name="id_number" value="{{ old('id_number', optional($shareholder)->id_number) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            @error('id_number')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- KRA PIN --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">KRA PIN</label>
            <input type="text" name="kra_pin" value="{{ old('kra_pin', optional($shareholder)->kra_pin) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>

        {{-- Joined At --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Joined At</label>
            <input type="date" name="joined_at"
                   value="{{ old('joined_at', optional($shareholder)->joined_at ? \Carbon\Carbon::parse($shareholder->joined_at)->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            @error('joined_at')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Is Active --}}
        <div class="flex items-center mt-6">
            <input type="checkbox" name="is_active" value="1"
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                   {{ old('is_active', optional($shareholder)->is_active) ? 'checked' : '' }}>
            <label class="ml-2 text-sm font-medium text-gray-700">Is Active</label>
        </div>

        {{-- Is Board Member --}}
        <div class="flex items-center mt-6">
            <input type="checkbox" name="is_board_member" value="1"
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                   {{ old('is_board_member', optional($shareholder)->is_board_member) ? 'checked' : '' }}>
            <label class="ml-2 text-sm font-medium text-gray-700">Is Board Member</label>
        </div>

    </div>
</div>
