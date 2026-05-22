<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── Stat Cards ─────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <x-stat-card
                    label="Total Listings"
                    :value="$stats['total_properties']"
                    icon="home"
                    color="indigo"
                    :href="route('properties.index')"
                />
                <x-stat-card
                    label="Active Listings"
                    :value="$stats['active_properties']"
                    icon="check"
                    color="green"
                    :href="route('properties.index')"
                />
                <x-stat-card
                    label="Total Inquiries"
                    :value="$stats['total_inquiries']"
                    icon="inbox"
                    color="blue"
                    :href="route('inquiries.index')"
                />
                <x-stat-card
                    label="New This Week"
                    :value="$stats['new_inquiries_week']"
                    icon="calendar"
                    color="amber"
                    :href="route('inquiries.index', ['status' => 'new'])"
                />
            </div>

            {{-- ── Status Breakdowns ───────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Properties by Status --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-5">
                        Listings by Status
                    </h3>
                    <div class="space-y-4">
                        @foreach(\App\Enums\PropertyStatus::cases() as $status)
                            @php
                                $count = (int) ($stats['properties_by_status'][$status->value] ?? 0);
                                $total = $stats['total_properties'] ?: 1;
                                $pct   = round($count / $total * 100);
                                $bars  = [
                                    'active'   => 'bg-green-500',
                                    'inactive' => 'bg-slate-300',
                                    'sold'     => 'bg-red-500',
                                    'rented'   => 'bg-blue-500',
                                ];
                                $barColor = $bars[$status->value] ?? 'bg-slate-300';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <x-status-badge :status="$status" />
                                    <span class="text-sm font-bold text-slate-800">
                                        {{ $count }}
                                        <span class="text-xs font-normal text-slate-400">({{ $pct }}%)</span>
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all duration-500"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Inquiries by Status --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-5">
                        Inquiries by Status
                    </h3>
                    <div class="space-y-4">
                        @foreach(\App\Enums\InquiryStatus::cases() as $status)
                            @php
                                $count = (int) ($stats['inquiries_by_status'][$status->value] ?? 0);
                                $total = $stats['total_inquiries'] ?: 1;
                                $pct   = round($count / $total * 100);
                                $bars  = [
                                    'new'        => 'bg-blue-500',
                                    'in_review'  => 'bg-amber-500',
                                    'contacted'  => 'bg-purple-500',
                                    'closed'     => 'bg-slate-300',
                                ];
                                $barColor = $bars[$status->value] ?? 'bg-slate-300';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <x-status-badge :status="$status" />
                                    <span class="text-sm font-bold text-slate-800">
                                        {{ $count }}
                                        <span class="text-xs font-normal text-slate-400">({{ $pct }}%)</span>
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all duration-500"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Top 5 Listings by Inquiries ─────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        Top 5 Listings by Inquiries
                    </h3>
                    <a href="{{ route('properties.index') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                        View all →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Property</th>
                                <th class="px-6 py-3 text-left">Agent</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-center">Inquiries</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($topListings as $rank => $property)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                                            {{ $rank === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $rank + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-900 max-w-[220px] truncate">{{ $property->title }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $property->city }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">
                                        {{ $property->agent?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$property->status" />
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('inquiries.index') }}?property={{ $property->id }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                                  bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors">
                                            {{ $property->inquiries_count }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('properties.edit', $property) }}"
                                           class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-sm">
                                        No listings yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Recent Inquiries ────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        Recent Inquiries
                    </h3>
                    <a href="{{ route('inquiries.index') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                        View all →
                    </a>
                </div>
                @if($recentInquiries->isEmpty())
                    <p class="px-6 py-10 text-center text-sm text-slate-400">No inquiries yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Inquirer</th>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Agent</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Received</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($recentInquiries as $inquiry)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-3">
                                            <p class="font-semibold text-slate-900">{{ $inquiry->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $inquiry->email }}</p>
                                        </td>
                                        <td class="px-6 py-3 text-slate-700 max-w-[180px]">
                                            <p class="truncate">{{ $inquiry->property?->title ?? '—' }}</p>
                                        </td>
                                        <td class="px-6 py-3 text-slate-500 text-xs">
                                            {{ $inquiry->property?->agent?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-3">
                                            <x-status-badge :status="$inquiry->status" />
                                        </td>
                                        <td class="px-6 py-3 text-xs text-slate-400 whitespace-nowrap">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('inquiries.show', $inquiry->id) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
