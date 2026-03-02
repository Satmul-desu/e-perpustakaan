{{-- components/order-status-badge.blade.php --}}
@props(['status'])

@php
$colors = [
    'pending' => 'bg-warning text-dark',
    'processing' => 'bg-info text-dark',
    'shipped' => 'bg-primary',
    'delivered' => 'bg-success',
    'completed' => 'bg-success',
    'cancelled' => 'bg-danger',
    'paid' => 'bg-success',
    'unpaid' => 'bg-secondary',
];
$colorClass = $colors[$status] ?? 'bg-secondary';
@endphp

<span class="badge {{ $colorClass }}">
    {{ ucfirst($status) }}
</span>

