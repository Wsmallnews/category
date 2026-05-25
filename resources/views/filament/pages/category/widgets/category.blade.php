<x-filament-widgets::widget>
    <livewire:sn-category-fi-category 
        :properties="$properties"
        :category-type="$record"
        :contained="$contained"
        :key="'components-' . $record->id . '-' . $record->level" />
</x-filament-widgets::widget>
