<x-public-layout>
    <x-slot name="title">{{ $property->title }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Back --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-8 transition-colors font-medium group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Listings
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Image + Details --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Main Image --}}
                <div class="rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm aspect-video">
                    @if($property->featured_image)
                        <img
                            src="{{ asset('storage/' . $property->featured_image) }}"
                            alt="{{ $property->title }}"
                            class="w-full h-full object-cover"
                        />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-3">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="1" points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            <span class="text-sm font-medium">No image available</span>
                        </div>
                    @endif
                </div>

                {{-- Gallery --}}
                @if($property->images && $property->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($property->images->take(4) as $image)
                            <div class="rounded-xl overflow-hidden aspect-square bg-slate-100 border border-slate-200">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="Property image"
                                     class="w-full h-full object-cover hover:opacity-90 transition-opacity cursor-pointer" />
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Property Info --}}
                <div class="bg-white rounded-2xl p-7 border border-slate-200 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ $property->title }}</h1>
                            <p class="mt-2 text-slate-500 flex items-center gap-1.5 text-sm">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $property->address }}, {{ $property->city }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-indigo-600">{{ $property->formatted_price }}</p>
                            <div class="mt-1.5"><x-status-badge :status="$property->status" /></div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Type</p>
                            <p class="mt-1.5 font-bold text-slate-800 capitalize">{{ $property->type->label() }}</p>
                        </div>
                        @if($property->bedrooms !== null)
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Bedrooms</p>
                            <p class="mt-1.5 font-bold text-slate-800">{{ $property->bedrooms }}</p>
                        </div>
                        @endif
                        @if($property->bathrooms !== null)
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Bathrooms</p>
                            <p class="mt-1.5 font-bold text-slate-800">{{ $property->bathrooms }}</p>
                        </div>
                        @endif
                        @if($property->area)
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Area</p>
                            <p class="mt-1.5 font-bold text-slate-800">{{ number_format((float)$property->area) }} <span class="text-xs font-normal text-slate-500">sqft</span></p>
                        </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="mt-7">
                        <h2 class="text-base font-semibold text-slate-900 mb-3">Description</h2>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
                    </div>

                    {{-- Agent --}}
                    @if($property->agent)
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold">Listed by</p>
                            <p class="font-semibold text-slate-800 text-sm">{{ $property->agent->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Inquiry Form --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24">

                    <div class="mb-5 pb-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Enquire About This Property</h2>
                        <p class="mt-1 text-xs text-slate-500">Our agent will respond within 24 hours.</p>
                    </div>

                    @if(session('inquiry_sent'))
                        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 text-sm flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Your inquiry has been sent! An agent will be in touch shortly.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}" />

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Your Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" required
                                          class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                          placeholder="I'm interested in this property…">{{ old('message') }}</textarea>
                                @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors text-sm shadow-sm">
                                Send Inquiry
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-public-layout>
