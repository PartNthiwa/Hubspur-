<x-shop::layouts
    :title="__('Change Password')"
    :description="__('Secure your account')"
    :has-header="false"
    :has-footer="false"
>
    @include('mumbos::layouts.partials.admin-header')

    <div class="flex min-h-screen bg-gray-50">
        @include('mumbos::layouts.partials.admin-sidebar')

        <div class="flex-1 flex flex-col">
            <main class="p-6 flex-1">
                <div class="max-w-3xl mx-auto">
                    <a href="{{ route('shop.shareholders.profile') }}"
                       class="inline-flex items-center text-sm text-gray-600 hover:text-green-600 transition mb-6">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                    </a>

                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Change Password</h1>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('shop.shareholders.profile.change-password') }}" class="bg-white p-6 rounded shadow space-y-6">
                        @csrf

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="new_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Password</button>
                        </div>
                    </form>
                </div>
            </main>

            @include('mumbos::layouts.partials.footer')
        </div>
    </div>
</x-shop::layouts>
