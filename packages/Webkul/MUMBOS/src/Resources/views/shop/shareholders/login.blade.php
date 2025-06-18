<x-shop::layouts 
    :has-header="false" 
	:has-feature="false"
	:has-footer="false"
    :has-sidebar="false">
    
    <x-slot:title>
        {{ __('Register as Shareholder') }}
    </x-slot>
<main id="main" class="bg-white">
    <div class="container mt-20 max-1180:px-5 max-md:mt-12">
        <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
            <a href="{{ route('shop.home.index') }}" class="m-[0_auto_20px_auto]" aria-label="MUMBO">
                <img src="{{ asset('storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png') }}"
                     alt="MUMBO" width="131" height="29">
            </a>
        </div>

        <div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
            <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl"> Shareholder Login </h1>
            <p class="mt-4 text-xl text-zinc-500 max-sm:mt-0 max-sm:text-sm">
                Sign in to access your shareholder dashboard.
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <form method="POST" action="{{ route('shop.shareholders.login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="mb-2 block text-base max-sm:text-sm max-sm:mb-1 required"> Email </label>
                        <input type="email" name="email"
                               class="mb-1.5 w-full rounded-lg border px-6 py-4 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-md:py-3 max-sm:py-2 max-sm:text-sm"
                               placeholder="email@example.com" required>
                        @error('email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 block text-base max-sm:text-sm max-sm:mb-1 required"> Password </label>
                        <input type="password" name="password" id="password"
                               class="mb-1.5 w-full rounded-lg border px-6 py-4 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-md:py-3 max-sm:py-2 max-sm:text-sm"
                               placeholder="Password" required>
                        @error('password')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="remember" name="remember" class="accent-navyBlue">
                            <label for="remember" class="text-zinc-500 text-sm">Remember me</label>
                        </div>
                        <a href="{{ route('shop.shareholders.forgot-password') }}"
                           class="text-sm text-black hover:underline">Forgot Password?</a>
                    </div>

                    <button type="submit"
                            class="primary-button m-0 mx-auto block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
                New shareholder? <a class="text-navyBlue hover:underline" href="{{ route('shop.shareholders.register.create') }}">
                    Register here
                </a>
            </p>
        </div>

        <p class="mb-4 mt-8 text-center text-xs text-zinc-500">
            © {{ date('Y') }} MUMBO | THM Software. All rights reserved.
        </p>
    </div>
</main>
</x-shop::layouts>
