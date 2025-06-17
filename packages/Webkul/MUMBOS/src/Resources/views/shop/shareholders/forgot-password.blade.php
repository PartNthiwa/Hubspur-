<x-shop::layouts>

    <x-slot name="page_title">Forgot Password</x-slot>
<main id="main" class="bg-white">
    <div class="container mt-20 max-1180:px-5 max-md:mt-12">

        <!-- Logo and header -->
        <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
            <a href="{{ url('/') }}" class="m-[0_auto_20px_auto]" aria-label="Bagisto">
                <img src="{{ asset('storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png') }}" alt="MUMBO" width="131" height="29">
            </a>
        </div>

        <!-- Card -->
        <div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
            
            <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">Recover Password</h1>
            <p class="mt-4 text-xl text-zinc-500 max-sm:mt-0 max-sm:text-sm">
                If you forgot your password, recover it by entering your email address.
            </p>

            <!-- Status or error messages -->
            @if (session('status'))
                <div class="mt-6 bg-green-100 text-green-700 px-4 py-3 rounded text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 bg-red-100 text-red-700 px-4 py-3 rounded text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="mt-14 rounded max-sm:mt-8">
                <form method="POST" action="{{ route('shop.shareholders.forgot-password.send') }}" novalidate>
                    @csrf

                    <div class="mb-4 max-sm:mb-1.5">
                        <label class="mb-2 block text-base max-sm:text-sm max-sm:mb-1 required">Email</label>
                        <input type="email" name="email"
                               class="mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm px-6 py-4 max-sm:py-1.5"
                               placeholder="email@example.com" aria-label="Email" aria-required="true" required>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-9 max-sm:mt-0 max-sm:justify-center max-sm:text-center">
                        <button class="primary-button m-0 mx-auto block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5 max-sm:text-sm ltr:ml-0 rtl:mr-0" type="submit">
                            Reset Password
                        </button>
                    </div>

                    <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
                        Back to sign in?
                        <a class="text-navyBlue" href="{{ route('shop.shareholders.login') }}">
                            Sign In
                        </a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="mb-4 mt-8 text-center text-xs text-zinc-500">
            © Copyright 2012 - {{ date('Y') }}, THM Software. All rights reserved.
        </p>
    </div>
</main>

</x-shop::layouts>
