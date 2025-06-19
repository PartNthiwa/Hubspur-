<x-shop::layouts
    :title="__('Edit Shareholder Profile')"
    :description="__('Manage your profile and account details')"
    :keywords="__('shareholder, profile, account')"
    :has-header="false"
    :has-footer="false"
>
    {{-- Header --}}
    @include('mumbos::layouts.partials.admin-header')

    <div class="flex min-h-screen bg-gray-50">
        {{-- Sidebar --}}
        @include('mumbos::layouts.partials.admin-sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            <main class="p-6 flex-1">
                <div class="max-w-6xl mx-auto">
                      <div class="mb-4">
           <a href="{{ route('shop.shareholders.dashboard') }}"

               class="inline-flex items-center text-sm text-gray-600 hover:text-green-600 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>

                    {{-- Flash Success --}}
                    @if(session('success'))
                        <div class="mb-4 text-green-700 bg-green-100 border border-green-300 rounded px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="mb-4 text-red-700 bg-red-100 border border-red-300 rounded px-4 py-3">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Profile Update Form --}}
                    <form method="POST" action="{{ route('shop.shareholders.profile.update') }}" class="space-y-6 bg-white p-6 rounded-lg shadow">
                        @csrf

                        @php
                            $inputClass = "mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500";
                        @endphp

                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $shareholder->full_name) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $shareholder->email) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $shareholder->phone) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                            <input type="text" name="id_number" value="{{ old('id_number', $shareholder->id_number) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="kra_pin" class="block text-sm font-medium text-gray-700">KRA PIN</label>
                            <input type="text" name="kra_pin" value="{{ old('kra_pin', $shareholder->kra_pin) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="postal_address" class="block text-sm font-medium text-gray-700">Postal Address</label>
                            <input type="text" name="postal_address" value="{{ old('postal_address', $shareholder->postal_address) }}" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="physical_address" class="block text-sm font-medium text-gray-700">Physical Address</label>
                            <input type="text" name="physical_address" value="{{ old('physical_address', $shareholder->physical_address) }}" class="{{ $inputClass }}">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Update Profile
                            </button>
                        </div>
                    </form>

                    {{-- Change Password Section --}}
                    <div class="mt-10">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Change Password</h2>

                        <form method="POST" action="{{ route('shop.shareholders.profile.change-password') }}" class="space-y-6 bg-white p-6 rounded-lg shadow">
                            @csrf

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="new_password" class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="{{ $inputClass }}">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            {{-- Footer --}}
            @include('mumbos::layouts.partials.footer')
        </div>
    </div>
</x-shop::layouts>
