


<x-admin::layouts>


    <div class="mb-2 mt-8 flex items-center justify-between px-6">
        <h1 class="text-lg font-semibold text-gray-800">Members List</h1>
      
        <a href="{{ route('admin.shareholders.create') }}"
           class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            <x-heroicon-s-plus class="w-5 h-5" />
            <span class="text-sm font-medium">Add Member</span>
        </a>
    </div>


    <div
        class="w-full bg-white shadow-lg rounded-2xl overflow-hidden"
        x-data="{
            search: '',
            visibleCount: 0,
            highlight(text) {
                if (!this.search) return text;
                const term = this.search.toLowerCase();
                const regex = new RegExp(`(${term})`, 'gi');
                return text.replace(regex, '<mark class=\'bg-yellow-200\'>$1</mark>');
            },
            matches(row) {
                let term = this.search.toLowerCase();
                let name = row.dataset.name?.toLowerCase() || '';
                let number = row.dataset.number?.toLowerCase() || '';
                let matched = term === '' || name.includes(term) || number.includes(term);

                if (matched) this.visibleCount++;
                return matched;
            }
        }"
        x-init="$watch('search', () => visibleCount = 0)"
    >
        {{-- Filters --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200">
            <input type="text"
                   x-model.debounce.300="search"
                   placeholder="Search members by name or number…"
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

   
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-max text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-6 py-3 border border-gray-300 text-left">#</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Member Number</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Full Name</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Membership Types</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Membership Contribution</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Capital Contribution</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Incentives</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Assigned Shares</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Capital Shares</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Total Shares</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Total Contributions</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Status</th>
                        <th class="px-6 py-3 border border-gray-300 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white" x-init="visibleCount = 0">
                    @forelse($shareholders as $shareholder)
                        @php
                            $fullName = $shareholder->customer->first_name . ' ' . $shareholder->customer->last_name;
                            $membershipContribution = $shareholder->contributions->where('type', 'membership')->where('status', 'approved')->sum('amount');
                            $capitalContribution = $shareholder->contributions->where('type', 'capital')->where('status', 'approved')->sum('amount');
                            $nonMembershipTotal = $shareholder->contributions->filter(fn($c) => strtolower(trim($c->type)) !== 'membership' && $c->status === 'approved')->sum('amount');
                            $shareValue = $shareholder->phase->share_value ?? 1000;
                            $contributionShares = $shareValue > 0 ? $nonMembershipTotal / $shareValue : 0;
                            $incentiveShares = $shareholder->incentives->sum(fn($i) => $i->pivot->units ?? 0);
                            $assignedShares = $shareholder->shares->sum(fn($s)=> $s->pivot->units);
                            $totalShares = $contributionShares + $incentiveShares + $assignedShares;
                        @endphp

                        <tr x-show="matches($el)"
                            data-name="{{ strtolower($fullName) }}"
                            data-number="{{ strtolower($shareholder->shareholder_number) }}"
                            class="hover:bg-gray-100 hover:shadow-sm transition">
                            <td class="px-6 py-4 border">{{ $shareholder->id }}</td>
                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ $shareholder->shareholder_number }}`)"></span>
                            </td>
                            <td class="px-6 py-4 border">
                                <span x-html="highlight(`{{ $fullName }}`)"></span>
                            </td>
                            <td class="px-6 py-4 border">
                                @forelse ($shareholder->membershipTypes as $membership)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mr-1">
                                        {{ $membership->type }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-sm">–</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 border">KES {{ number_format($membershipContribution, 2) }}</td>
                            <td class="px-6 py-4 border">KES {{ number_format($capitalContribution, 2) }}</td>
                         <td class="px-4 py-3">
    @forelse ($shareholder->incentives as $incentive)
       <form
    method="POST"
    action="{{ route('admin.incentives.update-units', ['incentive_id' => $incentive->id, 'shareholder_number' => $shareholder->shareholder_number]) }}"
    class="inline-flex items-center gap-1 bg-indigo-100 text-black text-xs px-2 py-1 rounded mr-1"
>
    @csrf
    @method('PUT')

    <label class="font-semibold mr-1">{{ ucfirst($incentive->type) }}:</label>

    <input
        type="number"
        name="units"
        value="{{ $incentive->pivot->units ?? 0 }}"
        min="0"
        class="w-14 text-xs border border-gray-300 rounded px-1 py-0.5"
    />

    <button type="submit"
            class="text-green-700 hover:text-green-900 text-xs font-semibold border border-green-600 rounded px-2 py-0.5 bg-white">
        Update
    </button>
</form>

    @empty
        <span class="text-gray-400 text-sm">–</span>
    @endforelse
</td>

<td class="px-6 py-4 border">
    @forelse($shareholder->shares as $share)
        <div class="text-sm">
            {{ $share->class }} – {{ $share->pivot->units }} shares
        </div>
    @empty
        <span class="text-gray-400 italic">–</span>
    @endforelse
</td>





                            <td class="px-6 py-4 border">{{ number_format($contributionShares) }}</td>
                            <td class="px-6 py-4 border">{{ number_format($totalShares) }}</td>
                            <td class="px-6 py-4 border">KES {{ number_format($nonMembershipTotal, 2) }}</td>
                            <td class="px-6 py-4 border">
                                <span class="text-xs px-2 py-1 rounded {{ $shareholder->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                    {{ $shareholder->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border">
                                <div class="flex items-center gap-x-3">
                                    <a href="{{ route('admin.shareholders.show', $shareholder) }}"
                                       class="text-indigo-600 hover:text-indigo-800" title="View">
                                        <x-heroicon-s-eye class="w-5 h-5" />
                                    </a>
                                    <a href="{{ route('admin.shareholders.edit', $shareholder) }}"
                                       class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.shareholders.destroy', $shareholder->shareholder_number) }}"
                                          onsubmit="return confirm('Delete this shareholder?')"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="text-red-600 hover:text-red-800">
                                            <x-heroicon-s-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr x-show="visibleCount === 0">
                            <td colspan="12" class="px-6 py-4 text-center text-red-600">
                                Whoops!! No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- Shareholder Details Preview (if only 1 visible row) --}}
<div x-show="visibleCount === 1 && search.length > 0" x-cloak class="p-6  bg-gray-50 border-t border-gray-200">
    <div class="w-full mt-10 h-2 my-6 rounded-full bg-gradient-to-r from-green-600 via-blue-500 to-purple-600 shadow-md"></div>
<div style="height: 4px; background: linear-gradient(to right, green, blue, red);"></div>
    @php
        $firstVisible = $shareholders->first(); 
    @endphp
    <div class="max-w-4xl mx-auto text-left space-y-4">
        <h2 class="text-lg font-bold text-gray-800">Shareholder Details</h2>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Name:</p>
                <p class="font-medium text-gray-800">
                    $fullName = $shareholder->customer
    ? $shareholder->customer->first_name . ' ' . $shareholder->customer->last_name
    : 'N/A';

                </p>
            </div>
            <div>
                <p class="text-gray-500">Member Number:</p>
                <p class="font-medium text-gray-800">
                     {{ $firstVisible->shareholder_number ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Email:</p>
                <p class="font-medium text-gray-800">
                   {{ $firstVisible?->customer?->email ?? 'No email available' }}

                </p>
            </div>
            <div>
                <p class="text-gray-500">Phone:</p>
                <p class="font-medium text-gray-800">
                    {{ $firstVisible->phone ?? 'N/A' }}
                </p>
            </div>
           <div>
        @if ($firstVisible && $firstVisible->relationLoaded('contributions'))
    <div>
        <p class="text-gray-500 font-medium mb-1">Capital Contributions</p>
        <ul class="list-disc list-inside text-gray-700 space-y-1">
            @php
                $capitalGrouped = optional($firstVisible->contributions)
                    ? $firstVisible->contributions
                        ->where('type', 'capital')
                        ->where('status', 'approved')
                        ->groupBy(fn($c) => $c->phase->name ?? 'Unknown Phase')
                    : collect();
            @endphp

            @forelse ($capitalGrouped as $phaseName => $contributions)
                <li>
                    <span class="font-semibold">{{ $phaseName }}:</span>
                    KES {{ number_format($contributions->sum('amount'), 2) }}
                </li>
            @empty
                <li class="text-gray-400">No capital contributions found.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        <p class="text-gray-500 font-medium">Other Contributions (excluding capital & membership):</p>
        <p class="font-semibold text-gray-800">
            KES {{
                number_format(
                    $firstVisible->contributions
                        ->filter(fn($c) => !in_array(strtolower($c->type), ['membership', 'capital']) && $c->status === 'approved')
                        ->sum('amount'),
                    2
                )
            }}
        </p>
    </div>
@else
    <div class="text-gray-400 italic">
        Contribution details are not available.
    </div>
@endif


           <div>
    <p class="text-gray-500">Status:</p>
    <p class="font-medium text-gray-800">
        @if ($firstVisible)
            {{ $firstVisible->is_active ? 'Active' : 'Inactive' }}
        @else
            Not available
        @endif
    </p>
</div>


           <div class="mt-6 flex flex-wrap gap-4">

   @if ($firstVisible)
    <a href="{{ route('admin.shareholders.show', $firstVisible) }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition">
        <x-heroicon-s-eye class="w-5 h-5 mr-2" />
        View Full Profile
    </a>
@else
    <button disabled
        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 text-sm rounded-md cursor-not-allowed">
        <x-heroicon-s-eye class="w-5 h-5 mr-2" />
        No Profile
    </button>
@endif


   @if ($firstVisible)
    <a href="{{ route('admin.shareholders.edit', ['shareholder' => $firstVisible->shareholder_number]) }}"
       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition">
        <x-heroicon-s-pencil class="w-5 h-5 mr-2" />
        Edit Member
    </a>
@endif

@if ($firstVisible && optional($firstVisible->customer)->email)
    <a href="mailto:{{ $firstVisible->customer->email }}"
       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition">
        <x-heroicon-s-envelope class="w-5 h-5 mr-2" />
        Send Email
    </a>
@else
    <button disabled
            class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 text-sm rounded-md cursor-not-allowed">
        <x-heroicon-s-envelope class="w-5 h-5 mr-2" />
        No Email Available
    </button>
@endif

@if ($firstVisible && $firstVisible->shareholder_number)
    @php
        $statementUrl = route('admin.shareholders.statement', ['shareholder_number' => $firstVisible->shareholder_number]);
    @endphp

    <a href="{{ $statementUrl }}"
       onclick="showToast()"
       download
       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-indigo-600 text-white text-sm rounded-md transition">
        <x-heroicon-s-arrow-down-tray class="w-5 h-5 mr-2" />
        View/Download Statement
    </a>
@else
    <button disabled
            class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 text-sm rounded-md cursor-not-allowed">
        <x-heroicon-s-arrow-down-tray class="w-5 h-5 mr-2" />
        No Statement Available
    </button>
@endif




</div>


        </div>
    </div>
</div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            {{ $shareholders->links() }}
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div id="toast"
     style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            z-index: 9999; background-color: #1f2937; color: #fff;
            padding: 16px 28px; border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); font-size: 16px;">
    Generating statement...
</div>

   

<div class="w-full h-2 my-6 rounded-full bg-gradient-to-r from-green-600 via-blue-500 to-purple-600 shadow-md"></div>
<div style="height: 4px; background: linear-gradient(to right, green, blue, red);"></div>
 <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments Ltd. All rights reserved.
    </div>


<script>
    function showToast(message = 'Generating statement...') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>
</x-admin::layouts>

