<x-shop::layouts>
    <x-slot:title>
        {{ __('Reset Password') }}
    </x-slot>
    <main id="main" class="bg-white">
    <div class="container mt-20 max-1180:px-5 max-md:mt-12">
        <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
            <a href="{{ url('/') }}" class="m-[0_auto_20px_auto]" aria-label="Bagisto">
                <img src="{{ core()->getCurrentChannel()->logo_url }}" alt="MUMBO" width="131" height="29">
            </a>
        </div>

        <div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
            <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">
                Reset Password
            </h1>

            <p class="mt-4 text-xl text-zinc-500 max-sm:mt-0 max-sm:text-sm">
                Enter your new password to regain access to your account.
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <form method="POST" action="{{ route('mumbos.shareholders.password.reset') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-4 max-sm:mb-1.5">
                        <label class="mb-2 block text-base max-sm:text-sm max-sm:mb-1 required"> New Password </label>
                        <input type="password" name="password" class="mb-1.5 w-full rounded-lg border px-6 py-4 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm max-sm:py-1.5" placeholder="New password" required>
                    </div>

                    <div class="mb-4 max-sm:mb-1.5">
                        <label class="mb-2 block text-base max-sm:text-sm max-sm:mb-1 required"> Confirm Password </label>
                        <input type="password" name="password_confirmation" class="mb-1.5 w-full rounded-lg border px-6 py-4 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm max-sm:py-1.5" placeholder="Confirm new password" required>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-9 max-sm:mt-0 max-sm:justify-center max-sm:text-center">
                        <button type="submit" class="primary-button m-0 mx-auto block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5 max-sm:text-sm">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="mb-4 mt-8 text-center text-xs text-zinc-500">
            © {{ date('Y') }} MUMBO. All rights reserved.
        </p>
    </div>
</main>
</x-shop::layouts>