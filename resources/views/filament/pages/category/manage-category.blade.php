<x-filament-panels::page>
    {{ $this->form }}

    @if ($record)
        <livewire:sn-category-fi-category :category-type="$record" :properties="$this->getProperties()" :key="'components-' . $record->id . '-' . $record->level" />
    @endif
</x-filament-panels::page>