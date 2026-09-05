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

    /**
     * 解析分类类型：缺失时不抛错（categoryType 置空）
     */
    public function mountCategoryable()
    {
        $this->categoryType = Utils::getCategoryTypeModel()::scopeable(...$this->getScopeable())->when($this->categoryTypeId, function ($query) {
            $query->where('id', $this->categoryTypeId);
        })->first();

        $this->categoryTypeId = $this->categoryType?->id;
    }

    /**
     * 分类类型是否存在（缺失时调用方应短路，不发起分类查询）
     */
    public function hasCategoryType(): bool
    {
        return ! is_null($this->categoryType);
    }

    public function getScoped()
    {
        $scoped = [
            ...$this->getScopeable(),
            'type_id' => $this->categoryType?->id,
        ];
        has_tenancy() && $scoped['team_id'] = current_tenant()?->id;

        return $scoped;
    }

    /**
     * queryBuilder 不支持调用 Nestedset 的 scoped 方法
     *
     * 仅返回已限定 scope 的查询起点，查询条件（normal/defaultOrder 等）由调用方控制；
     * 分类类型不存在时返回 null，调用方应短路返回空集合，不要拿空 type_id 去查库。
     */
    public function getScopedQuery(): ?QueryBuilder
    {
        if (! $this->hasCategoryType()) {
            return null;
        }

        return Utils::getCategoryModel()::scoped($this->getScoped());
    }
}
