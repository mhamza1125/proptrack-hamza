<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ auth()->user()->hasRole('admin') ? 'All Inquiries' : 'My Property Inquiries' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif

            {{-- Status summary cards (admin only) --}}
            @role('admin')
                @php
                    $counts = \App\Models\Inquiry::selectRaw('status, COUNT(*) as total')
                        ->groupBy('status')->pluck('total', 'status');
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(\App\Enums\InquiryStatus::cases() as $s)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $counts[$s->value] ?? 0 }}</p>
                            <x-status-badge :status="$s" />
                        </div>
                    @endforeach
                </div>
            @endrole

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <form method="GET" action="{{ route('inquiries.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Search (name / email)</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                               placeholder="Search…"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div class="min-w-[160px]">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                        <select name="status"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Filter
                        </button>
                        @if(array_filter($filters))
                            <a href="{{ route('inquiries.index') }}"
                               class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                @if($inquiries->isEmpty())
                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="font-medium">No inquiries found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Inquirer</th>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    @role('admin')
                                        <th class="px-6 py-3 text-left">Agent</th>
                                    @endrole
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Received</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $inquiry->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $inquiry->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800 dark:text-gray-200 max-w-[200px] truncate">
                                                {{ $inquiry->property?->title ?? '—' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $inquiry->property?->city }}</p>
                                        </td>
                                        @role('admin')
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                                {{ $inquiry->property?->agent?->name ?? '—' }}
                                            </td>
                                        @endrole
                                        <td class="px-6 py-4">
                                            <x-status-badge :status="$inquiry->status" />
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('inquiries.show', $inquiry->id) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 font-medium transition-colors">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($inquiries->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $inquiries->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
