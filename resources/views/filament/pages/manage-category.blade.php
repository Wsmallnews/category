<x-filament-panels::page>
    {{-- Page content --}}
    {{ $this->form }}

    @if ($record)
        <livewire:sn-category-component :category-type="$record" />
    @endif
</x-filament-panels::page>