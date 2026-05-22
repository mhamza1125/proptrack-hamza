<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('properties.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Add New Property
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

                <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('properties.partials.form')

                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('properties.index') }}"
                           class="px-4 py-2.5 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <x-primary-button>Create Property</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
