<?php

namespace Wsmallnews\Category\Livewire\Concerns;

use Kalnoy\Nestedset\QueryBuilder;
use Livewire\Attributes\Locked;
use Wsmallnews\Category\Models\CategoryType as CategoryTypeModel;
use Wsmallnews\Category\Support\Utils;

trait Categoryable
{
    #[Locked]
    public ?int $categoryTypeId = null;

    public ?CategoryTypeModel $categoryType = null;

    public function mountCategoryable()
    {
        $this->categoryType = Utils::getCategoryTypeModel()::scopeable(...$this->getScopeable())->when($this->categoryTypeId, function ($query) {
            $query->where('id', $this->categoryTypeId);
        })->firstOrFail();

        $this->categoryTypeId = $this->categoryType->id;
    }

    public function getScoped()
    {
        $scoped = [
            ...$this->getScopeable(),
            'type_id' => $this->categoryType->id,
        ];
        general_has_tenancy() && $scoped['team_id'] = general_current_tenant()?->id;

        return $scoped;
    }

    /**
     * queryBuilder 不支持调用 Nestedset 的 scoped 方法
     */
    public function getScopedQuery(): string | QueryBuilder
    {
        return Utils::getCategoryModel()::scoped($this->getScoped());
    }
}
