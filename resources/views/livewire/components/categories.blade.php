@php
    $level = $this->getLevel();
    $categories = $this->getCategories();
@endphp

<ul
    class="w-full flex flex-col bg-primary-500"
    role="menu"
>
    @forelse($categories as $treeKey => $record)
        <x-dynamic-component @class([
                'w-full',
            ]) :component="$this->getItemView()" key="categories-component-{{ $record->getKey() }}"  :record="$record" :level="$level" :current-level="1" />
    @empty
        <li 
            class="w-full px-3 py-2 text-center"
        >
            {{ $this->getEmptyLabel() ?: '未获取到分类数据' }}
        </li>
    @endforelse
</ul>
