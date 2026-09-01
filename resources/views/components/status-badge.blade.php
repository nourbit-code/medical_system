@props(['status'])

@php
    $statusClasses = [
        'pending' => 'status-pending',
        'confirmed' => 'status-confirmed',
        'in_progress' => 'status-in-progress',
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled',
    ];

    $statusLabel = str_replace('_', ' ', $status);
@endphp

<span class="badge status-badge {{ $statusClasses[$status] ?? 'status-default' }}">
    {{ ucfirst($statusLabel) }}
</span>
