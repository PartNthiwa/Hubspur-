<x-shop::layouts>
    <x-slot:title>
        {{ __('Become a Shareholder - MUMBO Kenya Diaspora Investments') }}
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow space-y-8">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Become a Shareholder</h1>
            <p class="text-gray-600">Join MUMBO Kenya Diaspora Investments and be part of a transformative community investing in Kenya's future.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Types of Shareholding</h2>
            <div class="space-y-3">
                <div class="p-4 border rounded">
                    <h3 class="font-bold text-gray-800">Ordinary Shares</h3>
                    <p class="text-gray-600 text-sm">Standard ownership with voting rights. Ideal for long-term investors seeking growth and dividends.</p>
                </div>

                <div class="p-4 border rounded">
                    <h3 class="font-bold text-gray-800">Preferred Shares</h3>
                    <p class="text-gray-600 text-sm">Priority in dividends and capital payouts. Non-voting shares suitable for passive investors.</p>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Why Join Us?</h2>
            <ul class="list-disc pl-6 text-gray-600 space-y-1 text-sm">
                <li>Transparent and secure investment structure</li>
                <li>Access to periodic reports and dividends</li>
                <li>Opportunity to participate in general meetings and decisions</li>
                <li>Support for diaspora-led development initiatives</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Minimum Requirements</h2>
            <ul class="list-disc pl-6 text-gray-600 space-y-1 text-sm">
                <li>National ID or Passport</li>
                <li>Valid email address and phone number</li>
                <li>Willingness to invest in share units</li>
                <li>Ability to make contributions via Bank, M-PESA, Sendwave, or Taptap Send</li>
            </ul>
        </div>

        <div class="text-center">
            <a href="{{ route('shop.shareholders.register.create') }}"
               class="inline-block bg-green-600 hover:bg-green-700 text-black px-6 py-3 rounded font-semibold transition">
                Register as Shareholder
            </a>
        </div>
    </div>
</x-shop::layouts>
