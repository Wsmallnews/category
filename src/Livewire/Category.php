<?php

namespace Wsmallnews\Category\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Wsmallnews\Category\Models\Category as CategoryModel;

class Category extends Component
{
    // public int $parentId = 0;

    // public int | string $pageName;

    // public int | string $perPage;

    // public string $pageType;

    // public Collection $comments;

    // public array $pageInfo = [];

    // public bool $loadChildren = false;

    public function mount() {}

    public function getRecordLabel(Model $category): HtmlString | string
    {
        return $category->name_label;
    }

    public function getLevel(): ?int
    {
        return $this->categoryType?->level;
    }

    public function getCategories()
    {
        return $this->getScopedQuery()->normal()->defaultOrder()
            ->get()->toTree();
    }


    protected function nestedScoped()
    {
        return [
            'scope_type' => $this->categoryType?->scope_type,
            'scope_id' => $this->categoryType?->scope_id,
            'type_id' => $this->categoryType?->id,
        ];
    }


    // public function render()
    // {
    //     $categories = CategoryModel::query()->with(['children.children'])->where('parent_id', 0)->get();

    //     return view('sn-category::livewires.category', [
    //         'categories' => $categories,
    //     ])->title('分类列表');
    // }
}
