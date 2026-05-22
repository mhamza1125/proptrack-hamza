<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agent Panel — {{ auth()->user()->name }}
            </h2>
            <a href="{{ route('properties.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
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
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Stat Cards ──────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                        My Inquiries by Status
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(\App\Enums\InquiryStatus::cases() as $status)
                            @php
                                $count = (int) ($stats['inquiries_by_status'][$status->value] ?? 0);
                            @endphp
                            <a href="{{ route('inquiries.index', ['status' => $status->value]) }}"
                               class="flex flex-col items-center p-4 rounded-xl border border-gray-100 dark:border-gray-700
                                      hover:border-indigo-200 dark:hover:border-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20
                                      transition-colors group text-center">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    {{ $count }}
                                </span>
                                <div class="mt-1"><x-status-badge :status="$status" /></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── My Properties table ─────────────────────────────────────── --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                        My Properties
                    </h3>
                    <a href="{{ route('properties.index') }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                        Manage all →
                    </a>
                </div>

                @if($properties->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <p class="font-medium">No properties yet.</p>
                        <a href="{{ route('properties.create') }}"
                           class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                            Add your first property
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-center">Inquiries</th>
                                    <th class="px-6 py-3 text-center">Active</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @foreach($properties as $property)
                                    @php $hasActive = $property->active_inquiries_count > 0; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors
                                               {{ $hasActive ? 'border-l-2 border-l-amber-400' : '' }}">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900 dark:text-white max-w-[240px] truncate">
                                                {{ $property->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 capitalize">
                                                {{ $property->type->label() }} · {{ $property->city }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-status-badge :status="$property->status" />
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('inquiries.index') }}"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold
                                                      {{ $property->inquiries_count > 0 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                                {{ $property->inquiries_count }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($hasActive)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $property->active_inquiries_count }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('properties.edit', $property) }}"
                                                   class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                                    Edit
                                                </a>

                                                @if($hasActive)
                                                    {{-- Delete blocked — show tooltip-style indicator --}}
                                                    <span class="text-xs text-gray-400 dark:text-gray-600 cursor-not-allowed"
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
                                                                class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
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
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                            Recent Inquiries
                        </h3>
                        <a href="{{ route('inquiries.index') }}"
                           class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                            View all →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">From</th>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Received</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @foreach($recentInquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-3">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $inquiry->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $inquiry->email }}</p>
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-300 text-xs max-w-[180px]">
                                            <p class="truncate">{{ $inquiry->property?->title ?? '—' }}</p>
                                        </td>
                                        <td class="px-6 py-3">
                                            <x-status-badge :status="$inquiry->status" />
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('inquiries.show', $inquiry->id) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
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
