     {{-- Header --}}
           <!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

<header class="bg-green-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1>    
              <a href="{{ url('/') }}"
                           class="inline-flex items-center text-base text-white ">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Shop
                        </a> </h1>
    
        <nav class="space-x-4 text-sm font-medium flex items-center">
            <p>  {{ __('Member since') }}  {{ Auth::user()->shareholder->created_at->diffForHumans() }}</p>
            <a href="{{ route('shop.shareholders.profile') }}" class="hover:underline">Profile</a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-300 hover:text-white hover:underline">
                    Logout
                </button>
            </form>
        </nav>
    </div>
</header>
