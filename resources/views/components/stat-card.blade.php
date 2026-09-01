@props(['label', 'value', 'color' => 'primary', 'icon' => ''])

<div class="stat-card">
    <div class="stat-card-top">
        <span class="stat-label">{{ $label }}</span>
        <span class="stat-icon text-bg-{{ $color }}">{{ $icon }}</span>
    </div>
    <div class="stat-value">{{ $value }}</div>
</div>