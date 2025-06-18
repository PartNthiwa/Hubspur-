
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

>
  
    <x-slot:title>
        {{ __('Become a Shareholder - MUMBO Kenya Diaspora Investments') }}
    </x-slot>
   <div class="hidden">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    </div>
    <section class="p-0">
    <div class="relative h-72" style="background-image: url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d1?fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <div class="relative h-72" style="background-image: url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d1?fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center space-y-4">
        <h1 class="text-white text-3xl font-bold">Become a Shareholder</h1>
        <ol class="flex space-x-2 text-sm text-white">
            <li><a href="{{ url('/') }}" class="text-green-300 hover:underline">Home</a></li>
            <li>/</li>
            <li class="text-white">Become a Shareholder</li>
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
                            <li class="text-black mt-4">Become a Shareholder</li>
                        </ol>
                    </nav>

                    <h1 class="text-capitalize font-bold text-3xl mb-4">Become a Shareholder</h1>
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
        </div>

        <div class="flex flex-wrap justify-center gap-6">
            @foreach ($shareTypes as $share)
                <div class="flex items-center w-full md:w-1/2 lg:w-5/12 p-6 rounded-lg shadow-md {{ $loop->odd ? 'bg-green-100' : 'bg-blue-100' }}">
                    <img 
                        src="{{ $share->icon_url
                            ? asset('storage/' . ltrim($share->icon_url, '/'))
                            : asset('storage/channel/1/3DQKdWJJ0QGBbuDwF8apcQfDyNQCylNGqjqRM53p.png') }}"
                        alt="{{ $share->class }}" 
                        class="w-20 h-20 rounded-full mr-6 border-4 border-white shadow"
                    />

                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">{{ $share->class }}</h4>
                        <p class="text-sm text-gray-700 mt-1">
                            {{ $share->description ?? 'No description available.' }}
                        </p>

                        <p class="mt-2 text-xl text-gray-800 font-medium">
                            <em>Value</em><br>
                            <strong>KES {{ number_format($share->price_per_unit, 2) }}</strong>
                        </p>

                        <!-- One button per card, passing price_per_unit -->
                        <button
                            onclick="openModal({{ $share->id }}, {{ $share->price_per_unit }}, '{{ addslashes($share->class) }}')"
                            class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                        >
                            Register
                        </button>
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




    <section class="py-5 bg-gradient-to-r from-green-600 to-blue-600 text-white">
        <div class="container text-center">
            <h2 class="text-2xl mb-4 font-bold mb-4">Join MUMBO Shareholding Community Today</h2>
            <a href="{{ route('shop.shareholders.register.create') }}" class="btn btn-lg  bg-white text-green-700 font-semibold text-uppercase px-6 py-3 rounded">
                Register Today
            </a>
        </div>
    </section>
   <div id="shareRegisterModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">&times;</button>

        <h3 class="text-lg font-bold mb-4">
            Register for <span id="modalShareClass" class="underline"></span>
        </h3>

        <form method="POST" action="{{ route('shop.shares.register') }}">
            @csrf
            <input type="hidden" name="share_id" id="modalShareId">
            <input type="hidden" id="unitValue" value="">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Amount (KES)</label>
                <input
                    type="number"
                    name="total_value"
                    id="totalValueInput"
                    required
                    min="1"
                    class="w-full mt-1 p-2 border rounded"
                    oninput="calculateUnits()"
                >
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Estimated Units</label>
                <input
                    type="text"
                    id="estimatedUnits"
                    readonly
                    class="w-full mt-1 p-2 bg-gray-100 rounded"
                >
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
</div>
<script>
    function openModal(shareId, pricePerUnit, shareClass) {
        document.getElementById('modalShareId').value    = shareId;
        document.getElementById('unitValue').value       = pricePerUnit;
        document.getElementById('modalShareClass').textContent = shareClass;
        document.getElementById('totalValueInput').value = '';
        document.getElementById('estimatedUnits').value  = '';

        const modal = document.getElementById('shareRegisterModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('shareRegisterModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function calculateUnits() {
        const total = parseFloat(document.getElementById('totalValueInput').value);
        const price = parseFloat(document.getElementById('unitValue').value);

        const estimated = document.getElementById('estimatedUnits');
        if (total > 0 && price > 0) {
            const units = Math.floor(total / price);
            estimated.value = units > 0 ? units : 0;
        } else {
            estimated.value = '';
        }
    }
</script>


</x-shop::layouts>
