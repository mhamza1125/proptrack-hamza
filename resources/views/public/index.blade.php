<x-public-layout>
    <x-slot name="title">Property Listings</x-slot>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">Find Your Perfect Property</h1>
            <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                Browse thousands of verified listings — houses, apartments, commercial spaces and land.
            </p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <form method="GET" action="{{ route('home') }}" class="flex flex-wrap gap-3 items-end">

                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Title, city or address…"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                {{-- City --}}
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">City</label>
                    <select name="city" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Min Price ($)</label>
                    <input
                        type="number"
                        name="min_price"
                        value="{{ $filters['min_price'] ?? '' }}"
                        placeholder="0"
                        min="0"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                {{-- Max Price --}}
                <div class="min-w-[130px]">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Max Price ($)</label>
                    <input
                        type="number"
                        name="max_price"
                        value="{{ $filters['max_price'] ?? '' }}"
                        placeholder="Any"
                        min="0"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Search
                    </button>
                    @if(array_filter($filters))
                        <a href="{{ route('home') }}"
                           class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </section>

    {{-- Listings Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Result count --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span class="font-semibold">{{ $properties->firstItem() ?? 0 }}</span>–<span class="font-semibold">{{ $properties->lastItem() ?? 0 }}</span>
                of <span class="font-semibold">{{ $properties->total() }}</span> properties
            </p>
        </div>

        @if($properties->isEmpty())
            <div class="text-center py-20 text-gray-500 dark:text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <p class="text-lg font-medium">No properties found</p>
                <p class="text-sm mt-1">Try adjusting your search filters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $properties->links() }}
            </div>
        @endif
    </section>

</x-public-layout>
