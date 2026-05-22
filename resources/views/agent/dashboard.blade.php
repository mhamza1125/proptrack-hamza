<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    Agent Panel
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('properties.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Property
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Stat Cards ──────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <x-stat-card
                    label="My Listings"
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

            {{-- ── Inquiry Status Breakdown ────────────────────────────────── --}}
            @if($stats['total_inquiries'] > 0)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-5">
                        My Inquiries by Status
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(\App\Enums\InquiryStatus::cases() as $status)
                            @php
                                $count = (int) ($stats['inquiries_by_status'][$status->value] ?? 0);
                            @endphp
                            <a href="{{ route('inquiries.index', ['status' => $status->value]) }}"
                               class="flex flex-col items-center p-4 rounded-xl border border-slate-100
                                      hover:border-indigo-200 hover:bg-indigo-50 transition-colors group text-center">
                                <span class="text-2xl font-extrabold text-slate-800 group-hover:text-indigo-600">
                                    {{ $count }}
                                </span>
                                <div class="mt-2"><x-status-badge :status="$status" /></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── My Properties table ─────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        My Properties
                    </h3>
                    <a href="{{ route('properties.index') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                        Manage all →
                    </a>
                </div>

                @if($properties->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <div class="w-14 h-14 mx-auto mb-4 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-slate-600">No properties yet.</p>
                        <a href="{{ route('properties.create') }}"
                           class="mt-2 inline-block text-sm text-indigo-600 hover:underline font-medium">
                            Add your first property
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-center">Inquiries</th>
                                    <th class="px-6 py-3 text-center">Active</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($properties as $property)
                                    @php $hasActive = $property->active_inquiries_count > 0; @endphp
                                    <tr class="hover:bg-slate-50 transition-colors
                                               {{ $hasActive ? 'border-l-2 border-l-amber-400' : '' }}">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-900 max-w-[240px] truncate">{{ $property->title }}</p>
                                            <p class="text-xs text-slate-400 capitalize mt-0.5">{{ $property->type->label() }} · {{ $property->city }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-status-badge :status="$property->status" />
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('inquiries.index') }}"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                                      {{ $property->inquiries_count > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-400' }}">
                                                {{ $property->inquiries_count }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($hasActive)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $property->active_inquiries_count }}
                                                </span>
                                            @else
                                                <span class="text-slate-300 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('properties.edit', $property) }}"
                                                   class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                                                    Edit
                                                </a>

                                                @if($hasActive)
                                                    <span class="text-xs text-slate-300 cursor-not-allowed"
                                                          title="Cannot delete: {{ $property->active_inquiries_count }} active {{ Str::plural('inquiry', $property->active_inquiries_count) }} (New/In Review)">
                                                        Delete
                                                    </span>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('properties.destroy', $property) }}"
                                                          onsubmit="return confirm('Delete this property? This cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-xs text-red-500 hover:text-red-700 font-semibold">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── Recent Inquiries ─────────────────────────────────────────── --}}
            @if($recentInquiries->isNotEmpty())
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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">From</th>
                                    <th class="px-6 py-3 text-left">Property</th>
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
                                        <td class="px-6 py-3 text-slate-600 text-xs max-w-[180px]">
                                            <p class="truncate">{{ $inquiry->property?->title ?? '—' }}</p>
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
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
