@props([
    'label',
    'value',
    'icon'    => 'chart',
    'color'   => 'indigo',
    'href'    => null,
    'suffix'  => null,
])

@php
$colors = [
    'indigo' => 'bg-indigo-50 text-indigo-600',
    'green'  => 'bg-green-50 text-green-600',
    'blue'   => 'bg-blue-50 text-blue-600',
    'amber'  => 'bg-amber-50 text-amber-600',
    'red'    => 'bg-red-50 text-red-600',
    'purple' => 'bg-purple-50 text-purple-600',
];
$iconColor = $colors[$color] ?? $colors['indigo'];

$icons = [
    'home'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    'check'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'inbox'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>',
    'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
    'chart'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
];
$iconPath = $icons[$icon] ?? $icons['chart'];
@endphp

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5
     {{ $href ? 'hover:shadow-md transition-shadow cursor-pointer hover:border-indigo-200' : '' }}">
    @if($href)<a href="{{ $href }}" class="block">@endif

    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                {{ $label }}
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($value) }}
                @if($suffix)<span class="text-lg font-normal text-slate-400 ml-1">{{ $suffix }}</span>@endif
            </p>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $iconColor }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconPath !!}
            </svg>
        </div>
    </div>

    @if($href)</a>@endif
</div>
