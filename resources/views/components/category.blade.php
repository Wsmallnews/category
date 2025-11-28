@props(['record', 'level', 'current-level'])

@php
    $hasChild = $record->children->count() > 0;
    $paddingLeftClass = 'pl-' . $currentLevel * 4;
    $currentLevel++;
@endphp

<li
    @if ($hasChild)
        x-data="{ isExpanded: {{ $record->has_active ? 'true' : 'false' }} }"
        aria-controls="accordionItem{{$record->id}}"
        :aria-expanded="isExpanded ? 'true' : 'false'"
        aria-haspopup="true"
    @endif
    role="menuitem"
>
    <a @class([
            'flex w-full h-14 justify-between items-center pr-4 font-bold text-white gap-2',
            'bg-primary-600' => $record->has_active,
            $paddingLeftClass,
        ])
        @if ($hasChild)
            @click="isExpanded = ! isExpanded"
            href="javascript:;"
        @else
            {{ \Filament\Support\generate_href_html('https://www.taobao.com') }}
        @endif
    >
        {{ $this->getRecordLabel($record) }}
        @if ($hasChild)
            <x-filament::icon icon="heroicon-m-chevron-down" class="size-6 font-bold transform transition-transform duration-300" ::class="isExpanded ? 'rotate-180' : ''" aria-hidden="true" />
        @endif
    </a>

    @if ($hasChild) 
        <ul class="w-full flex flex-col"
            id="accordionItemCategory{{$record->id}}"
            x-cloak x-show="isExpanded"
            aria-labelledby="controlsAccordionItemOne{{$record->id}}"
            x-collapse
            role="menu"
        >
            @foreach ($record->children as $child)
                <x-dynamic-component @class([
                        'w-full',
                    ]) :component="$this->getItemView()" key="tree-component-{{ $child->getKey() }}"  :record="$child" :level="$level" :current-level="$currentLevel" />
            @endforeach
        </ul>
    @endif 
</li>
