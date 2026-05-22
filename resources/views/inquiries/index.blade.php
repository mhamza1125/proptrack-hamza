<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ auth()->user()->hasRole('admin') ? 'All Inquiries' : 'My Property Inquiries' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm font-medium">{{ session('error') }}</div>
            @endif

            {{-- Status summary cards (admin only) --}}
            @role('admin')
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(\App\Enums\InquiryStatus::cases() as $s)
                        <a href="{{ route('inquiries.index', ['status' => $s->value]) }}"
                           class="bg-white rounded-xl border border-slate-200 p-5 text-center hover:border-indigo-200 hover:shadow-sm transition-all group">
                            <p class="text-2xl font-extrabold text-slate-800 group-hover:text-indigo-600">{{ $statusCounts[$s->value] ?? 0 }}</p>
                            <div class="mt-2"><x-status-badge :status="$s" /></div>
                        </a>
                    @endforeach
                </div>
            @endrole

            {{-- Filters --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <form method="GET" action="{{ route('inquiries.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Search</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                               placeholder="Name or email…"
                               class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                    </div>
                    <div class="min-w-[160px]">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Status</label>
                        <select name="status"
                                class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
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
                                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            Filter
                        </button>
                        @if(array_filter($filters))
                            <a href="{{ route('inquiries.index') }}"
                               class="px-4 py-2.5 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                @if($inquiries->isEmpty())
                    <div class="text-center py-16 text-slate-400">
                        <div class="w-14 h-14 mx-auto mb-4 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-slate-600">No inquiries found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
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
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-900">{{ $inquiry->name }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $inquiry->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-slate-800 max-w-[200px] truncate">{{ $inquiry->property?->title ?? '—' }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $inquiry->property?->city }}</p>
                                        </td>
                                        @role('admin')
                                            <td class="px-6 py-4 text-slate-500 text-sm">
                                                {{ $inquiry->property?->agent?->name ?? '—' }}
                                            </td>
                                        @endrole
                                        <td class="px-6 py-4">
                                            <x-status-badge :status="$inquiry->status" />
                                        </td>
                                        <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('inquiries.show', $inquiry->id) }}"
                                               class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($inquiries->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $inquiries->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
