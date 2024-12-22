@props([
    'categories' => []
])

@foreach($categories as $category)
    <tr class="bg-gray-50 dark:bg-white/5">
        <td>
            {{ $category->name }}
        </td>
        <td>
            {{ $category->description }}
        </td>
    </tr>
    @if ($category->children->isNotEmpty())
        <x-sn-category::category-item :categories="$category->children" />
    @endif

@endforeach
