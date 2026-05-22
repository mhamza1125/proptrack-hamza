<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ auth()->user()->hasRole('admin') ? 'All Properties' : 'My Properties' }}
            </h2>
            @can('create', \App\Models\Property::class)
                <a href="{{ route('properties.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Property
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                @if($properties->isEmpty())
                    <div class="text-center py-20 text-slate-400">
                        <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-slate-600">No properties yet.</p>
                        @can('create', \App\Models\Property::class)
                            <a href="{{ route('properties.create') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline font-medium">Add your first property</a>
                        @endcan
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Property</th>
                                    <th class="px-6 py-3 text-left">Type</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Price</th>
                                    <th class="px-6 py-3 text-left">City</th>
                                    <th class="px-6 py-3 text-center">Inquiries</th>
                                    @if(auth()->user()->hasRole('admin'))
                                        <th class="px-6 py-3 text-left">Agent</th>
                                    @endif
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($properties as $property)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($property->featured_image)
                                                    <img src="{{ asset('storage/' . $property->featured_image) }}"
                                                         class="w-12 h-12 object-cover rounded-lg border border-slate-200" alt="" />
                                                @else
                                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 border border-slate-200">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-semibold text-slate-900 max-w-xs truncate">{{ $property->title }}</p>
                                                    <p class="text-xs text-slate-400 mt-0.5">{{ $property->address }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $property->type->label() }}</td>
                                        <td class="px-6 py-4"><x-status-badge :status="$property->status" /></td>
                                        <td class="px-6 py-4 font-bold text-indigo-600">{{ $property->formatted_price }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{ $property->city }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                                {{ ($property->inquiries_count ?? 0) > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-400' }}">
                                                {{ $property->inquiries_count ?? 0 }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4 text-slate-600">{{ $property->agent?->name ?? '—' }}</td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('listings.show', $property->id) }}"
                                                   class="text-xs text-slate-400 hover:text-indigo-600 transition-colors"
                                                   title="View public page">View</a>

                                                @can('update', $property)
                                                    <a href="{{ route('properties.edit', $property) }}"
                                                       class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('delete', $property)
                                                    <form method="POST" action="{{ route('properties.destroy', $property) }}"
                                                          onsubmit="return confirm('Delete this property? This cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($properties->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $properties->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
