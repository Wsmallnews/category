<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use Illuminate\Support\Str;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Filament\Pages\Category\Components\BaseCategory;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

abstract class Base extends BaseCategory
{
    use Scopeable;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?CategoryType $categoryType = null;

    public function mount(): void
    {
        $this->categoryType = $this->getCategoryType();
    }

    public function getCategoryType(): ?CategoryType
    {
        $categoryType = CategoryType::query()
            ->firstOrCreate(
                static::getScopeable(),
                [
                    'name' => Str::title(static::getScopeType()),
                    'level' => static::getLevel(),
                    'status' => CategoryTypeStatus::Normal,
                ]
            );

        return $categoryType;
    }
}
