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
       <div class="ml-4 mt-6 mb-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard</h1>
                <p class="text-gray-600">
                    Welcome,
                    <span class="text-emerald-700 font-semibold">
                        {{ $shareholder->customer->full_name }} — {{ $shareholder->shareholder_number }}
                    </span>
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    This is your dashboard. Here you can view your shareholdings and their details.
                </p>
            </div>

            {{-- Shareholder Status --}}
            <div class="flex items-center space-x-3">
            @php
                $status = strtolower($shareholder->status ?? 'active'); // e.g. 'active', 'pending', 'suspended'
                $statusColors = [
                    'active' => 'text-green-600 bg-green-100',
                    'pending' => 'text-yellow-600 bg-yellow-100',
                    'suspended' => 'text-red-600 bg-red-100',
                ];
                $pulseColors = [
                    'active' => 'bg-green-500',
                    'pending' => 'bg-yellow-500',
                    'suspended' => 'bg-red-500',
                ];
            @endphp

            <!-- Beeping Dot -->
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $pulseColors[$status] ?? 'bg-gray-400' }}"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $pulseColors[$status] ?? 'bg-gray-400' }}"></span>
            </span>

            <!-- Status Text -->
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$status] ?? 'text-gray-600 bg-gray-100' }}">
                @switch($status)
                    @case('active')
                        Active
                        @break
                    @case('pending')
                        Pending
                        @break
                    @case('suspended')
                        Suspended
                        @break
                    @default
                        Unknown
                @endswitch
            </span>
        </div>

        </div>
    </div>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 mt-4">

                <!-- Capital Contributions by Phase -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-4">Capital Contributions by Phase</h3>
                    <div x-data="chartComponent('bar', {{ Js::from($capitalLabels) }}, [{
                        label: 'Capital Contributions',
                        data: {{ Js::from($capitalData) }},
                        backgroundColor: 'rgba(34,197,94,0.6)'
                    }])" x-init="render()" class="relative h-[300px]">
                        <canvas></canvas>
                    </div>
                </div>

                <!-- Monthly Contributions -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-4">Contribution Timeline</h3>
                    <div x-data="chartComponent('line', {{ Js::from($timelineLabels) }}, [{
                        label: 'Monthly Contributions',
                        data: {{ Js::from($timelineData) }},
                        backgroundColor: 'rgba(59,130,246,0.4)',
                        borderColor: 'rgba(59,130,246,1)',
                        fill: true
                    }])" x-init="render()" class="relative h-[300px]">
                        <canvas></canvas>
                    </div>
                </div>

                <!-- Shares Owned -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-4">Share Distribution</h3>
                    <div x-data="chartComponent('pie', {{ Js::from($shareLabels) }}, [{
                        label: 'Shares Owned',
                        data: {{ Js::from($shareData) }},
                        backgroundColor: [
                            'rgba(34,197,94,0.6)',
                            'rgba(59,130,246,0.6)',
                            'rgba(244,63,94,0.6)',
                            'rgba(167,139,250,0.6)',
                            'rgba(250,204,21,0.6)'
                        ]
                    }])" x-init="render()" class="relative h-[300px]">
                        <canvas></canvas>
                    </div>
                </div>

                <!-- Incentives Earned Over Time -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-4">Incentives Earned</h3>
                    <div x-data="chartComponent('bar', {{ Js::from($incentiveLabels) }}, [{
                        label: 'Incentives',
                        data: {{ Js::from($incentiveData) }},
                        backgroundColor: 'rgba(250,204,21,0.6)'
                    }])" x-init="render()" class="relative h-[300px]">
                        <canvas></canvas>
                    </div>
                </div>

                <!-- Contributions by Type per Phase (Stacked) -->
                <div class="bg-white p-4 rounded shadow col-span-1 md:col-span-2">
                    <h3 class="text-lg font-bold mb-4">Phase-wise Contribution Breakdown</h3>
                    <div x-data="chartComponent('bar', {{ Js::from($phases) }}, {{ Js::from($stackedData) }}, true)" x-init="render()" class="relative h-[350px]">
                        <canvas></canvas>
                    </div>
                </div>

            </div>
         
              @include('mumbos::layouts.partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function chartComponent(type, labels, datasets, stacked = false) {
            return {
                render() {
                    const canvas = this.$el.querySelector('canvas');
                    if (!canvas) {
                        console.warn('No canvas found');
                        return;
                    }

                    new Chart(canvas.getContext('2d'), {
                        type: type,
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'top' }
                            },
                            scales: stacked ? {
                                x: { stacked: true },
                                y: { stacked: true, beginAtZero: true }
                            } : {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            };
        }
    </script>
</x-shop::layouts>
