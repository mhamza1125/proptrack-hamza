<x-public-layout>
    <x-slot name="title">Property Listings</x-slot>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 tracking-tight">Find Your Perfect Property</h1>
            <p class="text-indigo-200 text-lg max-w-2xl mx-auto leading-relaxed">
                Browse verified listings — houses, apartments, commercial spaces and land.
            </p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <form method="GET" action="{{ route('home') }}" class="flex flex-wrap gap-3 items-end">

                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Title, city or address…"
                        class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                    />
                </div>

                {{-- City --}}
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">City</label>
                    <select name="city" class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Type --}}
                <div class="min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Type</label>
                    <select name="type" class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" {{ ($filters['type'] ?? '') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Min Price --}}
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Min Price</label>
                    <input
                        type="number"
                        name="min_price"
                        value="{{ $filters['min_price'] ?? '' }}"
                        placeholder="$0"
                        min="0"
                        class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                    />
                </div>

                {{-- Max Price --}}
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Max Price</label>
                    <input
                        type="number"
                        name="max_price"
                        value="{{ $filters['max_price'] ?? '' }}"
                        placeholder="Any"
                        min="0"
                        class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                    />
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Search
                    </button>
                    @if(array_filter($filters))
                        <a href="{{ route('home') }}"
                           class="px-4 py-2.5 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </section>

    {{-- Listings Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Result count --}}
        <div class="flex items-center justify-between mb-8">
            <p class="text-sm text-slate-500">
                Showing
                <span class="font-semibold text-slate-800">{{ $properties->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-800">{{ $properties->lastItem() ?? 0 }}</span>
                of <span class="font-semibold text-slate-800">{{ $properties->total() }}</span> properties
            </p>
        </div>

        @if($properties->isEmpty())
            <div class="text-center py-24 text-slate-400">
                <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-slate-700">No properties found</p>
                <p class="text-sm mt-2 text-slate-400">Try adjusting your search filters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                @foreach($properties as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
        @endif
    </section>

</x-public-layout>
