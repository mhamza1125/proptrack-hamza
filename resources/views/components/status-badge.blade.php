@props(['status'])

@php
    $colors = [
        'active'    => 'bg-green-100 text-green-800 ring-green-600/20',
        'inactive'  => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        'sold'      => 'bg-red-100 text-red-800 ring-red-600/20',
        'rented'    => 'bg-blue-100 text-blue-800 ring-blue-600/20',
        'new'       => 'bg-blue-100 text-blue-800 ring-blue-600/20',
        'in_review' => 'bg-amber-100 text-amber-800 ring-amber-600/20',
        'contacted' => 'bg-purple-100 text-purple-800 ring-purple-600/20',
        'closed'    => 'bg-slate-100 text-slate-700 ring-slate-600/20',
    ];

    $value = $status instanceof \BackedEnum ? $status->value : $status;
    $label = $status instanceof \UnitEnum ? $status->label() : ucfirst(str_replace('_', ' ', $value));
    $color = $colors[$value] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $color }}">
    {{ $label }}
</span>
