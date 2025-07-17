<x-shop::layouts
    :title="__('Request Invitation')"
    :description="__('Request an invitation to become a shareholder')"
    :keywords="__('shareholder, contact, invite request')"
    :has-header="false"
    :has-footer="false"
>
    {{-- Header --}}
    @include('mumbos::layouts.partials.admin-header')

    <div class="flex min-h-screen bg-gray-100">
        {{-- Sidebar --}}
        @include('mumbos::layouts.partials.admin-sidebar')
       @php
            $hour = now()->hour;
            $shareholder = auth()->user()->shareholder;
            $name = $shareholder->full_name ?? auth()->user()->name;

            if ($hour < 12) {
                $greeting = 'Good morning';
                $icon = '☀️';
            } elseif ($hour < 17) {
                $greeting = 'Good afternoon';
                $icon = '🌤️';
            } else {
                $greeting = 'Good evening';
                $icon = '🌙';
            }
        @endphp


        <div class="flex-1 flex flex-col">
            <main class="p-8">
                <div class="mt-10 max-w-4xl mx-auto bg-white p-10 shadow rounded-xl border border-green-100">

                    {{-- Page Header --}}
                    <div class="text-center mb-10">
                        <h1 class="text-4xl font-bold text-green-800">Make an Inquiry</h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-2xl mx-auto flex items-center justify-center gap-2">
                        <span class="text-2xl">{{ $icon }}
                        <span>{{ $greeting }},</span> <strong>{{ $name }}</strong>. <br> <br>  <small>If you’d like to make an inquiry or request support, please fill out the form below.</small> 
                    </p>


                    </div>

                    {{-- Contact Info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mb-12">
                        <div class="bg-green-50 rounded-xl p-6 border border-green-100 shadow-sm hover:shadow-md transition">
                            <div class="text-green-600 text-3xl mb-2">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-md font-semibold text-gray-800">support@mumbodiaspora.org</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-6 border border-green-100 shadow-sm hover:shadow-md transition">
                            <div class="text-green-600 text-3xl mb-2">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-md font-semibold text-gray-800">+254 758 042 467</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-6 border border-green-100 shadow-sm hover:shadow-md transition">
                            <div class="text-green-600 text-3xl mb-2">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="text-md font-semibold text-gray-800">MUMBO Village, Ndengelwa-Kenya</p>
                        </div>
                    </div>

                    {{-- Contact Form --}}
                    <div class="mt-10">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Submit Your Interest</h2>

                        @if (session('info'))
                            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded">
                                {{ session('info') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('support.support') }}" class="space-y-6">
                            @csrf

                            @if ($errors->any())
                                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @php
                                $shareholder = auth()->user()->shareholder;
                            @endphp

                          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <input type="text" name="name"
                                    value="{{ $shareholder->full_name ?? auth()->user()->name }}"
                                    readonly
                                    class="bg-gray-100 border border-gray-300 rounded-xl px-4 py-3 w-full cursor-not-allowed text-gray-600 focus:outline-none">

                                <input type="email" name="email"
                                    value="{{ $shareholder->email ?? auth()->user()->email }}"
                                    readonly
                                    class="bg-gray-100 border border-gray-300 rounded-xl px-4 py-3 w-full cursor-not-allowed text-gray-600 focus:outline-none">
                            </div>


                            <textarea name="message" rows="5"
                                      placeholder="Make your inquiry , we're happy to help at MUMBO..."
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none"
                                      required>{{ old('message') }}</textarea>

                            <div class="text-right">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 font-medium shadow transition">
                                    <i class="fas fa-paper-plane"></i> Inquire Now
                                </button>
                            </div>
                        </form>
                         <div class="mt-4 text-center text-sm text-gray-500">
                            &copy; {{ date('Y') }}. All rights reserved.
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    @include('mumbos::layouts.partials.footer')
</x-shop::layouts>
