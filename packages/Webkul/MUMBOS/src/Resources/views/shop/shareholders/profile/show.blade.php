<x-shop::layouts
    :title="__('Shareholder Profile')"
    :description="__('Manage your profile and account details')"
    :keywords="__('shareholder, profile, account')"
    :has-header="false"
    :has-footer="false"
>
    {{-- Fixed Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md" style="height: 64px;">
        @include('mumbos::layouts.partials.admin-header')
    </header>

    <div class="flex flex-1 bg-gray-100" style="padding-top: 64px; padding-bottom: 48px; min-height: 100vh;">
        {{-- Fixed Sidebar --}}
        <aside class="fixed top-[64px] left-0 bottom-[48px] w-64 bg-white shadow-md overflow-auto">
            @include('mumbos::layouts.partials.admin-sidebar')
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 ml-64 p-8 overflow-auto" style="max-height: calc(100vh - 64px - 48px);">
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

                {{-- Profile Card --}}
                <div class="bg-white shadow rounded-lg p-8">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 text-base text-gray-700">
                        <div>
                            <dt class="font-semibold text-gray-600">Full Name</dt>
                            <dd>{{ $shareholder->customer->full_name }}</dd>
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
                            <dt class="font-semibold text-3xl text-gray-600">Joined At</dt>
                            <dd>{{ \Carbon\Carbon::parse($shareholder->joined_at)->format('F j, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Quote --}}
                <div class="mt-6 text-center text-sm text-gray-700 italic max-w-xl mx-auto px-4">
                    “At MUMBO, your shares aren’t just numbers — they’re your stake in Kenya’s growth and your journey towards financial empowerment.” <br> <br> - Eng Mark Meso
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    @include('mumbos::layouts.partials.footer')
</x-shop::layouts>
