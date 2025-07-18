
<x-shop::layouts
    :title="__('Become a Shareholder - MUMBO Kenya Diaspora Investments')"
    :description="__('Join MUMBO Kenya Diaspora Investments and be part of a transformative community investing in Kenya\'s future. We offer you a secure, transparent and rewarding way to participate in impactful ventures.')"
    :keywords="__('shareholder, investment, Kenya, diaspora, MUMBO')"
    :canonical="route('shop.shareholders.register.info')"
    :og-title="__('Become a Shareholder - MUMBO Kenya Diaspora Investments')"
    :og-description="__('Join MUMBO Kenya Diaspora Investments and be part of a transformative community investing in Kenya\'s future. We offer you a secure, transparent and rewarding way to participate in impactful ventures.')"
    :og-image="asset('storage/channel/1/shareholder-banner.jpg')"
    :og-url="route('shop.shareholders.register.info')"
    :og-type="'website'"
    :og-site-name="__('MUMBO Kenya Diaspora Investments')"
    :og-image-width="1200"
    :og-image-height="630"
    :twitter-title="__('Become a Shareholder - MUMBO Kenya Diaspora Investments')"
    :twitter-description="__('Join MUMBO Kenya Diaspora Investments and be part of a transformative community investing in Kenya\'s future. We offer you a secure, transparent and rewarding way to participate in impactful ventures.')"
    :twitter-image="asset('storage/channel/1/shareholder-banner.jpg')"
    :twitter-card="'summary_large_image'"
    :twitter-site="@config('mumbo.shop.twitter_handle')"
    :twitter-creator="@config('mumbo.shop.twitter_handle')"
    :twitter-url="route('shop.shareholders.register.info')"
    :twitter-image-width="1200"
    :twitter-image-height="630"
    :has-header="false" 
    :has-footer="false"
>


      @include('mumbos::layouts.partials.header')



      </style>
    <x-slot:title>
        {{ __('Become a Member - MUMBO Kenya Diaspora Investments') }}
    </x-slot>
  
    <section class="p-0">
    <div class="relative h-72" style="background-image: url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d1?fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <div class="relative h-72" style="background-image: url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d1?fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center space-y-4">
        <h1 class="text-white text-5xl font-bold">Become a Member</h1>
        <ol class="flex space-x-2 text-sm text-white">
            <li><a href="{{ url('/') }}" class="text-green-300 hover:underline">Home</a></li>
            <li>/</li>
            <li class="text-white">Become a Member</li>
        </ol>
    </div>
</div>

</div>

        <div class="container py-5">
            <div class="row">
                <div class="col-md-8">
                   <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="flex items-center space-x-2 text-sm text-gray-200">
                            <li class="mt-4">
                                <a href="{{ url('/') }}" class="text-green-300 hover:underline">Home</a>
                            </li>
                            <li class="mt-4">/</li>
                            <li class="text-black mt-4">Become a Member</li>
                        </ol>
                    </nav>

                    <h1 class="text-capitalize font-bold text-3xl mb-4">Become a Member</h1>
                    <p class="text-gray-700">
                        Join MUMBO Kenya Diaspora Investments and be part of a transformative community investing in Kenya's future.
                        We offer you a secure, transparent and rewarding way to participate in impactful ventures.
                    </p>
                </div>
            </div>
        </div>
    </section>

<section class="bg-gray-100 py-10">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold uppercase text-gray-800">Shareholding Categories</h2>
        <p><i> <small> Shareholding is by invitation only</small></i></p>

        </div>

        <div class="flex flex-wrap justify-center gap-6">
         @foreach ($membershipTypes as $type)
    @php
        $descriptionStripped = strip_tags($type->description);
        $shortDesc = Str::limit($descriptionStripped, 160);
    @endphp

    <div class="flex items-center w-full md:w-1/2 lg:w-5/12 p-6 rounded-lg shadow-md {{ $loop->odd ? 'bg-green-100' : 'bg-blue-100' }}">
        <img 
            src="{{ $type->icon_url
                ? asset('storage/' . ltrim($type->icon_url, '/'))
                : asset('storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png') }}"
            alt="{{ $type->name }}" 
            class="w-20 h-20 rounded-full mr-6 border-4 border-white shadow"
        />

        <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-800">{{ $type->name }}</h4>

            <p class="text-sm text-gray-700 mt-1 leading-relaxed">
                <span id="short-desc-{{ $type->id }}">{{ $shortDesc }}</span>
                <span id="full-desc-{{ $type->id }}" class="hidden">{!! nl2br(e($descriptionStripped)) !!}</span>

                @if(strlen($descriptionStripped) > 160)
                    <button
                        onclick="toggleReadMore({{ $type->id }})"
                        id="toggle-btn-{{ $type->id }}"
                        class="text-blue-600 hover:underline ml-1 text-sm"
                    >
                        Read more
                    </button>
                @endif
            </p>

            <p class="mt-2 text-xl text-gray-800 font-medium">
                <em>Value</em><br>
                <strong>KES {{ number_format($type->share_value ?? 0) }}</strong>
            </p>

           <a
    href="{{ route('contact') }}?membership={{ urlencode($type->name) }}"
    class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
>
    Express Interest
</a>
   
        </div>
    </div>
@endforeach


        </div>
    </div>
</section>
<section class="py-10 px-10 mb-10 mt-10 flex  h-[1000px] items-center">
    <div class="container mx-auto flex flex-col md:flex-row items-center gap-8 ">
        <!-- Image -->
       <div class="w-full md:w-1/2 pl-8" >
            <img src="{{ asset('storage/channel/1/why choose us.png') }}"
                 alt="Why Join Us" class=" w-full h-auto"> <br><br>
                 </div>
        <div class="w-full md:w-1/2 pr-8">
                  <h3 class="text-2xl font-bold mb-4">What you get</h3>
                <ul class="space-y-4 text-gray-700">
                <li class="flex items-start group">
                    <i class="fa fa-check-circle text-green-500 mr-3 mt-1"></i>
                    <span class="group-hover:text-green-600 transition duration-200">
                        Transparent and secure investment structure
                    </span>
                </li>
                <li class="flex items-start group">
                    <i class="fa fa-check-circle text-green-500 mr-3 mt-1"></i>
                    <span class="group-hover:text-green-600 transition duration-200">
                        Access to reports and dividends
                    </span>
                </li>
                <li class="flex items-start group">
                    <i class="fa fa-check-circle text-green-500 mr-3 mt-1"></i>
                    <span class="group-hover:text-green-600 transition duration-200">
                        Participate in decision-making and AGMs
                    </span>
                </li>
                <li class="flex items-start group">
                    <i class="fa fa-check-circle text-green-500 mr-3 mt-1"></i>
                    <span class="group-hover:text-green-600 transition duration-200">
                        Diaspora-driven development impact
                    </span>
                </li>
            </ul> <br>
            <h3 class="text-2xl font-bold mb-4">What you need</h3>
           <ul class="space-y-3 text-gray-700">
            <li class="flex items-start group">
                <i class="fa fa-check-circle text-green-600 mr-3 mt-1"></i> 
                <span class="group-hover:text-green-600 transition duration-200">
                    National ID or Passport
                </span>
            </li>
            <li class="flex items-start group">
                <i class="fa fa-check-circle text-green-600 mr-3 mt-1"></i> 
                <span class="group-hover:text-green-600 transition duration-200">
                    Valid email and phone number
                </span>
            </li>
            <li class="flex items-start group">
                <i class="fa fa-check-circle text-green-600 mr-3 mt-1"></i> 
                <span class="group-hover:text-green-600 transition duration-200">
                    Willingness to invest in share units
                </span>
            </li>
            <li class="flex items-start group">
                <i class="fa fa-check-circle text-green-600 mr-3 mt-1"></i> 
                <span class="group-hover:text-green-600 transition duration-200">
                    Payment options: M-PESA, Sendwave, Bank
                </span>
            </li>
        </ul>
        </div>
       
    </div>
</section>




    <section class="py-5 bg-gradient-to-r from-green-600 to-blue-600 text-white mb-8">
        <div class="container text-center">
            <h2 class="text-2xl mb-6 font-bold mb-4">Join MUMBO Shareholding Community Today</h2>
            <a href="{{ route('contact') }}" class="btn btn-lg  bg-white text-green-700 font-semibold text-uppercase px-6 py-3 rounded">
                Register Today
            </a>
        </div>
    </section>


<!-- Who We Are Section -->
<section id="who-we-are" class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Who We Are</h2>
        <p class="text-lg text-gray-600 leading-relaxed">
            MUMBO Kenya Diaspora Investments is a member-led collective of diaspora professionals and entrepreneurs channeling resources into Kenya’s future through structured and transparent investments.
        </p>

        <!-- Heroicon instead of image -->
        <div class="mt-8 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.5"
                 stroke="currentColor"
                 class="w-32 h-32 text-green-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17.982 18.725A7.488 7.488 0 0012 16.5a7.488 7.488 0 00-5.982 2.225M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm6 7.5a7.5 7.5 0 10-15 0 7.5 7.5 0 0015 0z" />
            </svg>
        </div>
    </div>
</section>


<!-- Mission & Vision Section -->
<section id="mission-vision" class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-10">Our Mission & Vision</h2>

    <div class="grid md:grid-cols-2 gap-10">
      <!-- Mission Card -->
      <div class="relative w-full h-72 bg-green-600 text-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center text-center">
        <!-- Heroicon -->
        <div style="background-color: white; border-radius: 9999px; padding: 0.75rem; margin-bottom: 1rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 011 1v7h3a1 1 0 110 2h-4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
          </svg>
        </div>

        <h3 class="text-xl font-semibold mb-2">Mission</h3>
        <p class="leading-relaxed">
          To empower the diaspora to drive economic growth by investing in sustainable, community-based projects in Kenya.
        </p>
      </div>

      <!-- Vision Card -->
      <div class="relative w-full h-72 bg-red-600 text-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center text-center">
        <!-- Heroicon -->
        <div style="background-color: white; border-radius: 9999px; padding: 0.75rem; margin-bottom: 1rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v5a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V5z" clip-rule="evenodd"/>
          </svg>
        </div>

        <h3 class="text-xl font-semibold mb-2">Vision</h3>
        <p class="leading-relaxed">
          A prosperous Kenya nurtured by its global diaspora, where collective investment transforms lives.
        </p>
      </div>
    </div>
  </div>
</section>


<!-- Leadership Section -->
<!-- Leadership Section -->
<section id="leadership" class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">Our Leadership Teams</h2>

        @foreach ($teams as $team)
            @if ($team->leaders->isNotEmpty())
                <div class="mb-12">
                    <h3 id="{{ Str::slug($team->name) }}" class="text-2xl font-semibold text-green-700 mb-6">
                        {{ $team->name }}
                    </h3>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($team->leaders as $leader)
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition p-6 flex flex-col items-center text-center">
                                <img src="{{ $leader->photo ? asset('storage/' . $leader->photo) : 'https://via.placeholder.com/150' }}"
                                     alt="{{ $leader->name }}"
                                     class="w-24 h-24 object-cover rounded-full mb-4 border-4 border-green-500 shadow">

                                <h4 class="text-lg font-bold text-gray-800">{{ $leader->name }}</h4>
                                <p class="text-sm text-green-600 font-medium mt-1">{{ $leader->position }}</p>

                                <p class="text-sm text-gray-600 mt-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($leader->bio), 100) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>



<script>
    function toggleReadMore(id) {
        const shortDesc = document.getElementById(`short-desc-${id}`);
        const fullDesc = document.getElementById(`full-desc-${id}`);
        const btn = document.getElementById(`toggle-btn-${id}`);

        const isCollapsed = shortDesc.style.display !== 'none';

        if (isCollapsed) {
            shortDesc.style.display = 'none';
            fullDesc.classList.remove('hidden');
            btn.textContent = 'Read less';
        } else {
            shortDesc.style.display = '';
            fullDesc.classList.add('hidden');
            btn.textContent = 'Read more';
        }
    }
</script>

  @include('mumbos::layouts.partials.footer')
</x-shop::layouts>
