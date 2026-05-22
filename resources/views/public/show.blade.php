<x-public-layout>
    <x-slot name="title">{{ $property->title }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Back --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Listings
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Image + Details --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Main Image --}}
                <div class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 aspect-video">
                    @if($property->featured_image)
                        <img
                            src="{{ asset('storage/' . $property->featured_image) }}"
                            alt="{{ $property->title }}"
                            class="w-full h-full object-cover"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Gallery --}}
                @if($property->images && $property->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($property->images->take(4) as $image)
                            <div class="rounded-lg overflow-hidden aspect-square bg-gray-100">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="Property image"
                                     class="w-full h-full object-cover hover:opacity-90 transition-opacity cursor-pointer" />
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Property Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $property->title }}</h1>
                            <p class="mt-1 text-gray-500 dark:text-gray-400 flex items-center gap-1 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $property->address }}, {{ $property->city }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $property->formatted_price }}</p>
                            <x-status-badge :status="$property->status" />
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Type</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white capitalize">{{ $property->type->label() }}</p>
                        </div>
                        @if($property->bedrooms !== null)
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Bedrooms</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $property->bedrooms }}</p>
                        </div>
                        @endif
                        @if($property->bathrooms !== null)
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Bathrooms</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $property->bathrooms }}</p>
                        </div>
                        @endif
                        @if($property->area)
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Area</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ number_format((float)$property->area) }} sqft</p>
                        </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
                    </div>

                    {{-- Agent --}}
                    @if($property->agent)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Listed by</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $property->agent->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Inquiry Form --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm sticky top-24">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Send Inquiry</h2>

                    @if(session('inquiry_sent'))
                        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 text-sm">
                            Your inquiry has been sent! An agent will contact you shortly.
                        </div>
                    @else
                        <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}" />

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                                <textarea name="message" rows="4" required
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="I'm interested in this property…">{{ old('message') }}</textarea>
                                @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors text-sm">
                                Send Inquiry
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
