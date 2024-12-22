<?php

namespace Wsmallnews\Category\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Wsmallnews\Category\Models\Category as CategoryModel;

class Category extends Component
{
    public int $parentId = 0;

    public int | string $pageName;

    public int | string $perPage;

    public string $pageType;

    public Collection $comments;

    public array $pageInfo = [];

    public bool $loadChildren = false;

    public function mount() {}

    public function render()
    {
        $categories = CategoryModel::query()->with(['children.children'])->where('parent_id', 0)->get();

        return view('sn-category::livewires.category', [
            'categories' => $categories,
        ])->title('分类列表');
    }
}
