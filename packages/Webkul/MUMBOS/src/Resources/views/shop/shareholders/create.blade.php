<x-shop::layouts>
    <x-slot:title>
        {{ __('Register as Shareholder') }}
    </x-slot>

    <main id="main" class="bg-white">
        <div class="container mt-20 max-1180:px-5 max-md:mt-12">
            <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9"><a href="http://127.0.0.1:8000" class="m-[0_auto_20px_auto]" aria-label="Bagisto"><img src="http://localhost:8000/storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png" alt="MUMBO" width="131" height="29"></a></div>
            <div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0 bg-white">
                
                <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">Register as Shareholder</h1>
                <p class="mt-4 text-xl text-zinc-500 max-sm:mt-0 max-sm:text-sm">Fill in your details to become a MUMBO Member.</p>

                @if (session('success'))
                    <div class="p-4 mt-6 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 mt-6 bg-red-900 text-red rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('shop.shareholders.register.store') }}" class="mt-10">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="First name" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="Last name" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="Phone Number" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="Email address" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="password" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded-lg px-5 py-3 text-gray-600 text-sm" placeholder="confirm password" required>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="primary-button w-full max-w-[374px] block mx-auto rounded-2xl px-11 py-4 text-base text-white bg-blue-600 hover:bg-blue-700 transition-all">
                            Register
                        </button>
                    </div>
                </form>

                    <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
                        Already have an account?
                        <a class="text-navyBlue" href="{{ route('shop.shareholders.login') }}">
                            Sign In
                        </a>
                    </p>
            </div>
            <p class="mb-4 mt-8 text-center text-xs text-zinc-500">© Copyright 2010 - 2025, THM Software. All rights reserved.</p>
        </div>
    </main>
</x-shop::layouts>
