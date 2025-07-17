<x-shop::layouts>
    <x-slot:title>Request Invitation</x-slot>

    <main class=" min-h-screen py-16 flex justify-center items-center">
        {{-- Constrained wrapper --}}
        <div class="w-full" style="max-width: 700px !important;">
            <div class="bg-green-50  p-10">

                {{-- Header --}}
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-green-800 mt-20">Request Invitation</h1>
                    <p class="mt-2 text-gray-600 text-lg">
                       Interested in becoming a shareholder? Fill out the form below to request an invitation.
                    </p>
                </div>

                {{-- Contact Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mb-10">
                    <div class="bg-white rounded-lg p-6 border shadow-sm">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600">Email</p>
                        <p class="text-md font-semibold text-gray-800">support@mumbodiaspora.org</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 border shadow-sm">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600">Phone</p>
                        <p class="text-md font-semibold text-gray-800">+254 758 042 467</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 border shadow-sm">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600">Location</p>
                        <p class="text-md font-semibold text-gray-800">MUMBO Village, Ndengelwa-Kenya</p>
                    </div>
                </div>

              
                <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Send Us a Message</h2>
                 

                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <input type="text" name="name" placeholder="Your Name"     required
                                class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-green-500">
                            <input type="email" name="email" placeholder="Your Email" required
                                class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-green-500">
                        </div>

                        <textarea name="message" rows="5" placeholder="Your Message" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500"></textarea>
                        <button type="submit"
                            class="mt-6 bg-green-100 hover:bg-green-500 text-green-800 border border-green-300 px-6 py-3 rounded-lg font-medium transition duration-200">
                            Send Message
                        </button>


                    </form>
                </div>

            </div>
        </div>
    </main>
</x-shop::layouts>
