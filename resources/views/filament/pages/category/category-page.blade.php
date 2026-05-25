@php
    $properties = static::getProperties();
    $canManage = static::getCanManage();
@endphp

<x-filament-panels::page>
    @if ($canManage)
        {{ $this->form }}
    @endif

    @if ($categoryType)
        <livewire:sn-category-fi-category 
            :properties="$properties"
            :category-type="$categoryType"
            :key="'fi-component-category-' . $categoryType->id . '-' . $categoryType->level"
        />
    @endif
</x-filament-panels::page>