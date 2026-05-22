@props(['property'])

@php
    use App\Services\ImageService;
    $imageService = app(ImageService::class);
    $thumb = $imageService->thumbnailUrl($property->featured_image);
@endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col">
    {{-- Image --}}
    <a href="{{ route('listings.show', $property->id) }}" class="block overflow-hidden">
        <div class="relative aspect-video overflow-hidden bg-slate-100">
            <img
                src="{{ $thumb }}"
                alt="{{ $property->title }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                onerror="this.src='https://placehold.co/600x338/e2e8f0/94a3b8?text=No+Image'"
            />
            <div class="absolute top-3 left-3">
                <x-status-badge :status="$property->status" />
            </div>
        </div>
    </a>

    {{-- Content --}}
    <div class="p-5 flex flex-col flex-1">
        {{-- Price --}}
        <p class="text-xl font-bold text-indigo-600">
            {{ $property->formatted_price }}
        </p>

        {{-- Title --}}
        <h3 class="mt-1.5 text-base font-semibold text-slate-900 leading-snug line-clamp-2">
            <a href="{{ route('listings.show', $property->id) }}" class="hover:text-indigo-600 transition-colors">
                {{ $property->title }}
            </a>
        </h3>

        {{-- Location --}}
        <p class="mt-2 text-sm text-slate-500 flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $property->city }}
        </p>

        {{-- Type + Specs --}}
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center flex-wrap gap-3 text-sm text-slate-600">
            <span class="font-medium text-slate-700">{{ $property->type->label() }}</span>

            @if($property->bedrooms !== null)
                <span class="flex items-center gap-1 text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ $property->bedrooms }} bed
                </span>
            @endif

            @if($property->bathrooms !== null)
                <span class="flex items-center gap-1 text-slate-500">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $property->bathrooms }} bath
                </span>
            @endif

            @if($property->area)
                <span class="text-slate-500">{{ number_format((float)$property->area) }} sqft</span>
            @endif
        </div>

        {{-- View Button --}}
        <div class="mt-4">
            <a href="{{ route('listings.show', $property->id) }}"
               class="block w-full text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors duration-150 shadow-sm">
                View Details
            </a>
        </div>
    </div>
</div>
