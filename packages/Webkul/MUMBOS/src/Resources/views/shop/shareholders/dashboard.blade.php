@extends('mumbos::layouts.shareholder-dashboard')
@section('content')

    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-black">Welcome, {{ $shareholder->name }}</h2>
                <p class="text-gray-600">Here is a summary of your shareholding.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-green-100 p-4 rounded shadow text-center">
                    <h3 class="text-sm text-gray-600 uppercase">Total Units</h3>
                    <p class="text-2xl font-bold text-green-700">{{ $totalUnits }}</p>
                </div>

                <div class="bg-blue-100 p-4 rounded shadow text-center">
                    <h3 class="text-sm text-gray-600 uppercase">Share Classes Owned</h3>
                    <p class="text-2xl font-bold text-blue-700">{{ $shares->count() }}</p>
                </div>

                <div class="bg-yellow-100 p-4 rounded shadow text-center">
                    <h3 class="text-sm text-gray-600 uppercase">Total Value (KES)</h3>
                    <p class="text-2xl font-bold text-yellow-700">KES {{ number_format($totalValue, 2) }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Shares</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 text-sm uppercase text-left">
                                <th class="py-3 px-4">Share Class</th>
                                <th class="py-3 px-4">Unit Value (KES)</th>
                                <th class="py-3 px-4">Units Held</th>
                                <th class="py-3 px-4">Total Value (KES)</th>
                                <th class="py-3 px-4">Date Registered</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @forelse ($shares as $share)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $share->class }}</td>
                                    <td class="py-3 px-4">{{ number_format($share->unit_value, 2) }}</td>
                                    <td class="py-3 px-4">{{ $share->pivot->units }}</td>
                                    <td class="py-3 px-4">{{ number_format($share->pivot->units * $share->unit_value, 2) }}</td>
                                    <td class="py-3 px-4">{{ $share->pivot->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">You have not registered for any share class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
