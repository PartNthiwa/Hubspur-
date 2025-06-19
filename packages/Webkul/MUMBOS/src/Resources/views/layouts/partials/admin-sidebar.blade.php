<aside class="w-64 bg-gray-100 shadow-md border-r hidden md:block">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold mb-6 text-black">Navigation</h2>
        <ul class="space-y-5 text-base font-medium">
            <li>
                <a href="{{ route('shop.shareholders.dashboard') }}"
                   class="inline-flex items-center text-black hover:text-green-300 transition">
                    <i class="fas fa-tachometer-alt text-lg mr-3"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('shop.shareholders.profile') }}"
                   class="inline-flex items-center text-black hover:text-green-300 transition">
                    <i class="fas fa-user text-lg mr-3"></i>
                    My Profile
                </a>
            </li>
            <li>
                <a href="{{ url('member.contributions') }}"
                   class="inline-flex items-center text-black hover:text-green-300 transition">
                    <i class="fas fa-donate text-lg mr-3"></i>
                    My Contributions
                </a>
            </li>
            <li>
                <a href="{{ url('member.certificate') }}"
                   class="inline-flex items-center text-black hover:text-green-300 transition">
                    <i class="fas fa-certificate text-lg mr-3"></i>
                    Certificate
                </a>
            </li>
            <li>
                <a href="{{ url('member.support') }}"
                   class="inline-flex items-center text-black hover:text-green-300 transition">
                    <i class="fas fa-headset text-lg mr-3"></i>
                    Support
                </a>
            </li>
        </ul>
    </div>
</aside>
