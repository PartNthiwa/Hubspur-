<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

<!-- Vanilla JS for dropdowns -->
<script>
  // Close any open dropdown when clicking outside
  document.addEventListener('click', function(e) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      const button = menu.previousElementSibling;
      if (!button.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  });

  // Toggle a dropdown by ID
  function toggleDropdown(id) {
    document.getElementById(id).classList.toggle('hidden');
  }
</script>

<header class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">

    {{-- Logo --}}
    <a href="{{ url('#') }}" class="block" aria-label="MUMBO">
      <img src="{{ asset('storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png') }}"
           alt="MUMBO" class="h-12 w-auto">
    </a>

    {{-- Navigation --}}
    <nav class="hidden md:flex space-x-8 text-sm font-medium">
      <a href="{{ url('/') }}" class="text-gray-700 hover:text-green-600 transition">Shop</a>

    <div class="relative">
  <button onclick="toggleDropdown('membership-menu')"
          class="text-gray-700 hover:text-green-600 transition focus:outline-none flex items-center">
    Membership <i class="fas fa-chevron-down ml-1 text-xs"></i>
  </button>
  <div id="membership-menu"
       class="dropdown-menu hidden absolute mt-2 w-56 bg-white rounded shadow-lg py-2 z-50">
    
    {{-- Register as Shareholder --}}
    <a href="{{ route('shop.shareholders.register.info') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
      Become a Member
    </a>

    {{-- Login --}}
    <a href="{{ route('shop.shareholders.login') }}"
   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
   Login to Portal
</a>


    {{-- Dashboard --}}
    <a href="{{ route('shop.shareholders.dashboard') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
      My Dashboard
    </a>
  </div>
</div>

      {{-- About Us Dropdown --}}
      <div class="relative">
        <button onclick="toggleDropdown('about-menu')"
                class="text-gray-700 hover:text-green-600 transition focus:outline-none flex items-center">
          About Us <i class="fas fa-chevron-down ml-1 text-xs"></i>
        </button>
        <div id="about-menu"
             class="dropdown-menu hidden absolute mt-2 w-48 bg-white rounded shadow-lg py-2 z-50">
          <a href="{{ url('/about') }}"
             class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Who We Are
          </a>
          <a href="{{ url('/mission') }}"
             class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Mission & Vision
          </a>
          <a href="{{ url('/team') }}"
             class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Leadership
          </a>
        </div>
      </div>

      <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-green-600 transition">Contact</a>
    </nav>

    {{-- Mobile menu icon (placeholder) --}}
    <div class="md:hidden">
      <button class="text-green-700 focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>
  </div>
</header>
