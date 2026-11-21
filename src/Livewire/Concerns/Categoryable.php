<?php

namespace Wsmallnews\Category\Livewire\Concerns;

use Kalnoy\Nestedset\QueryBuilder;
use Livewire\Attributes\Locked;
use Wsmallnews\Category\Models\CategoryType as CategoryTypeModel;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Livewire\Concerns\HasScopedType;

/**
 * Category tree helpers for a component: resolves its container type and
 * returns a query already constrained to one data space.
 *
 * Requires ScopedBySite on the host component. The data space defaults to the
 * site base scope; a host with its own partition overrides typeScopeable().
 */
trait Categoryable
{
    use HasScopedType;

    #[Locked]
    public ?int $categoryTypeId = null;

    public ?CategoryTypeModel $categoryType = null;

    public function mountCategoryable(): void
    {
        $this->categoryType = $this->resolveType();
        $this->categoryTypeId = $this->categoryType?->getKey();
    }

    /** Data space of the category tree; override to follow a partition. */
    protected function typeScopeable(): array
    {
        return $this->getBaseScopeable();
    }

    protected function typeModel(): string
    {
        return Utils::getCategoryTypeModel();
    }

    protected function typeIdValue(): ?int
    {
        return $this->categoryTypeId;
    }

    /** Scope + type id shared by the type and its rows. */
    public function getScoped()
    {
        return $this->scopedType();
    }

    /**
     * Query starting point of the category tree; null when the type is missing,
     * so callers must short-circuit instead of querying with an empty type_id.
     */
    public function getScopedQuery(): ?QueryBuilder
    {
        return $this->hasType()
            ? Utils::getCategoryModel()::scoped($this->scopedType())
            : null;
    }
}
