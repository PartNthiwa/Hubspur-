<x-shop::layouts
    :title="'Shareholder Card'"
    :description="'Your personal shareholder identification card'"
    :keywords="'shareholder, card, certificate'"
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
            <div class="max-w-[101%] mx-auto">
                {{-- Back Button --}}
                <div class="mb-6">
                    <a href="{{ route('shop.shareholders.dashboard') }}"
                       class="inline-flex items-center text-base text-gray-600 hover:text-green-600">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Member Card</h1>

                {{-- Shareholder Card Section --}}
                <div class="relative bg-white shadow-md rounded-lg border border-green-600 p-8 text-gray-800">
                    {{-- Logo --}}
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/mumbokenya.png') }}" alt="MUMBO Logo" class="mx-auto mb-2 w-40">
                        <div class="text-green-700 text-2xl font-semibold">
                            MUMBO Member Identification Card
                        </div>
                    </div>

                    {{-- Card Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <p><strong>Full Name:</strong> {{ optional(optional($shareholder)->customer)->full_name ?? '—' }}</p>
                            <p><strong>Shareholder Number:</strong> {{ $shareholder->shareholder_number ?? '—' }}</p>
                            <p><strong>Status:</strong>
                                @if(optional($shareholder)->is_active)
                                    <span class="text-green-600 font-semibold">Active</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p><strong>ID Number:</strong> {{ $shareholder->id_number ?? '—' }}</p>
                            <p><strong>Email:</strong> {{ $shareholder->email ?? '—' }}</p>
                            <p><strong>Issued:</strong> {{ now()->format('F j, Y') }}</p>
                        </div>
                    </div>

                    {{-- Stamp and "Digitally Issued" note --}}
                    <div class="absolute bottom-6 right-6 text-center">
                        <img src="{{ asset('images/digi.png') }}" alt="Digital Stamp" class="w-20 opacity-70">
                        <p class="text-xs text-gray-500 mt-1">Digitally Issued</p>
                    </div>
                </div>

                {{-- Download Button OUTSIDE the card --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('shop.shareholder.card.download') }}"
                       class="inline-block bg-green-600 text-white px-6 py-2 rounded font-semibold hover:bg-green-700 transition">
                        Download as PDF
                    </a>
                </div>

                {{-- Optional Quote --}}
                <div class="mt-6 text-center text-sm text-gray-700 italic max-w-xl mx-auto px-4">
                    “Your shareholder card is proof of your journey with MUMBO — empowering you to build, grow, and lead.”
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    @include('mumbos::layouts.partials.footer')
</x-shop::layouts>
