<x-shop::layouts
    :title="__('Shareholder Profile')"
    :description="__('Manage your profile and account details')"
    :keywords="__('shareholder, profile, account')"
    :has-header="false"
    :has-footer="false"
>
    {{-- Header --}}
    @include('mumbos::layouts.partials.admin-header')

    <div class="flex min-h-screen bg-gray-100">
        {{-- Sidebar --}}
        @include('mumbos::layouts.partials.admin-sidebar')

        <div class="flex-1 flex flex-col">
            <main class="p-8">
                <div class="max-w-6xl mx-auto">
                    
                    {{-- Back Button --}}
                    <div class="mb-6">
                        <a href="{{ route('shop.shareholders.dashboard') }}"
                           class="inline-flex items-center text-base text-gray-600 hover:text-green-600">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Profile</h1>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Profile Card --}}
                    <div class="bg-white shadow rounded-lg p-8">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 text-base text-gray-700">
                            <div>
                                <dt class="font-semibold text-gray-600">Full Name</dt>
                                <dd>{{ $shareholder->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Shareholder Number</dt>
                                <dd>{{ $shareholder->shareholder_number }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">ID Number</dt>
                                <dd>{{ $shareholder->id_number }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">KRA PIN</dt>
                                <dd>{{ $shareholder->kra_pin }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Email</dt>
                                <dd>{{ $shareholder->email }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Phone</dt>
                                <dd>{{ $shareholder->phone }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Postal Address</dt>
                                <dd>{{ $shareholder->postal_address }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Physical Address</dt>
                                <dd>{{ $shareholder->physical_address }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">City</dt>
                                <dd>{{ $shareholder->city ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Country</dt>
                                <dd>{{ $shareholder->country ?? '—' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="font-semibold text-gray-600 mb-1">Share Classes</dt>
                                <dd>
                                    @forelse ($shareholder->shares as $share)
                                        <span class="inline-flex items-center bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm mr-2 mb-2">
                                            {{ $share->class }} – {{ $share->pivot->units }} units
                                        </span>
                                    @empty
                                        <span class="text-gray-400">No shares allocated</span>
                                    @endforelse
                                </dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Total Units</dt>
                                <dd>{{ number_format($shareholder->share_units) }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Capital Paid</dt>
                                <dd>KES {{ number_format($shareholder->capital_paid, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-gray-600">Joined At</dt>
                                <dd>{{ \Carbon\Carbon::parse($shareholder->joined_at)->format('F j, Y') }}</dd>
                            </div>
                        </dl>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex flex-wrap gap-4">
                            <a href="{{ route('shop.shareholders.profile.edit') }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-md text-sm font-medium shadow">
                                <i class="fas fa-edit mr-2"></i> Edit Profile
                            </a>
                            <a href="{{ route('shop.shareholders.profile.change-password') }}"
                               class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-md text-sm font-medium shadow">
                                <i class="fas fa-key mr-2"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </main>

          
        </div>

    </div>
     
            @include('mumbos::layouts.partials.footer')
</x-shop::layouts>
