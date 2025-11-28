<?php

namespace Wsmallnews\Category\Livewire\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;

class Categories extends Base
{
    use Categoryable;

    public ?string $view = 'sn-category::livewire.components.categories';

    public ?string $itemView = 'sn-category::category';

    public ?string $style = 'simple';        // vivid=鲜明的, simple=简单的

    public function getRecordLabel(Model $category): HtmlString | string
    {
        return $category->name_label;
    }

    public function getLevel(): ?int
    {
        return $this->categoryType?->level;
    }

    public function getItemView(): string
    {
        return $this->itemView;
    }

    public function getCategories()
    {
        return $this->getScopedQuery()->normal()->defaultOrder()
            ->get()->toTree();
    }

    public function render()
    {
        return view($this->view);
    }
}
