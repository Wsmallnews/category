<?php

namespace Wsmallnews\Category\Livewire\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;
use Wsmallnews\FilamentNestedset\Livewire\Components\Nestedset;
use Wsmallnews\Support\Livewire\Concerns\Scopeable;

class Categories extends Nestedset
{
    use Categoryable;
    use Scopeable;

    public function getRecordLabel(Model $record): HtmlString | string
    {
        return $record->name_label;
    }

    public function getHasActive(Model $record): bool
    {
        return $record->has_active;
    }

    public function getNestedset(): Collection
    {
        return $this->getScopedQuery()->normal()->defaultOrder()
            ->get()->toTree();
    }

    public function render(): View
    {
        return view($this->view);
    }
}
