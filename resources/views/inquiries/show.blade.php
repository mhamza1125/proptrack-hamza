<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('inquiries.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Inquiry #{{ $inquiry->id }}
            </h2>
            <x-status-badge :status="$inquiry->status" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left: Inquiry details --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Inquirer --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Inquirer Details</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Name</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $inquiry->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</dt>
                                <dd class="mt-1 text-sm">
                                    <a href="mailto:{{ $inquiry->email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $inquiry->email }}
                                    </a>
                                </dd>
                            </div>
                            @if($inquiry->phone)
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a href="tel:{{ $inquiry->phone }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $inquiry->phone }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Received</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}
                                    <span class="text-gray-400 text-xs">({{ $inquiry->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-5">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Message</dt>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $inquiry->message }}</div>
                        </div>

                        {{-- Quick reply --}}
                        <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->property?->title }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Reply via Email
                            </a>
                        </div>
                    </div>

                    {{-- Property --}}
                    @if($inquiry->property)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">About the Property</h3>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $inquiry->property->title }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $inquiry->property->city }}</p>
                                    @if($inquiry->property->agent)
                                        <p class="text-xs text-gray-400 mt-1">Listed by {{ $inquiry->property->agent->name }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('listings.show', $inquiry->property->id) }}"
                                   class="shrink-0 text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                    View listing →
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right: Status management (admin only) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 sticky top-24">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Manage Status</h3>

                        @role('admin')
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Current status:</p>
                            <div class="mb-5"><x-status-badge :status="$inquiry->status" /></div>

                            <form method="POST" action="{{ route('inquiries.update-status', $inquiry) }}">
                                @csrf
                                @method('PATCH')

                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Change to:
                                </label>
                                <div class="space-y-2">
                                    @foreach($statuses as $status)
                                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors
                                            {{ $inquiry->status === $status
                                                ? 'border-indigo-300 bg-indigo-50 dark:border-indigo-600 dark:bg-indigo-900/30'
                                                : 'border-gray-200 dark:border-gray-600 hover:border-indigo-200 dark:hover:border-indigo-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            <input type="radio" name="status" value="{{ $status->value }}"
                                                   {{ $inquiry->status === $status ? 'checked' : '' }}
                                                   class="text-indigo-600 focus:ring-indigo-500" />
                                            <x-status-badge :status="$status" />
                                        </label>
                                    @endforeach
                                </div>

                                <button type="submit"
                                        class="mt-4 w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Update Status
                                </button>
                            </form>
                        @else
                            <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                                <x-status-badge :status="$inquiry->status" />
                                <p class="text-xs mt-3">Only admins can change inquiry status.</p>
                            </div>
                        @endrole
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
