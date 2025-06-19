<x-shop::layouts
    :title="__('Shareholder Dashboard')"
    :description="__('Dashboard overview of your shareholdings and activity')"
    :keywords="__('dashboard, shares, shareholder')"
    :has-header="false"
    :has-footer="false"
>
       @include('mumbos::layouts.partials.admin-header')
    <div class="flex min-h-screen bg-gray-200 text-gray-800">
  @include('mumbos::layouts.partials.admin-sidebar')

        <div class="flex-1 flex flex-col w-full">
          <div>
            <h1 class="text-2xl font-bold mt-6 mb-4 ml-4">{{ __('Dashboard') }}</h1>

           <p class="text-gray-600 mb-2 ml-4">
                {{ __('Welcome,') }} 
                <span class="text-emerald-700 font-semibold">
                    {{ Auth::user()->shareholder->full_name }} - {{ Auth::user()->shareholder->shareholder_number }} 
                  
                </span>
                <br><br>
                {{ __('This is your dashboard. Here you can view your shareholdings and their details.') }}
            </p>

        </div>
   
            <main class="flex-1 p-6">
                <h2 class="text-2xl font-bold mb-6">{{ __('My Shareholdings') }}</h2>

                @if ($shareholder->shares->isEmpty())
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                        <p>{{ __('You have not purchased any shares yet.') }}</p>
                        <a href="{{ route('shop.shareholders.register.info') }}" class="inline-block mt-2 text-green-600 hover:underline">
                            {{ __('Browse Share Types') }}
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto bg-white shadow rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Share Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value (KES)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($shareholder->shares as $share)
                                    @php
                                        $units = $share->pivot->units;
                                        $price = $share->price_per_unit;
                                        $total = $units * $price;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $share->class }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($units) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">KES {{ number_format($price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">KES {{ number_format($total, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $share->pivot->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>

            {{-- Optional Footer --}}
            <footer class="bg-green-500 border-t p-4 text-center text-sm text-white">
                &copy; {{ date('Y') }} MUMBO Kenya. All rights reserved.
            </footer>
        </div>
    </div>

</x-shop::layouts>
