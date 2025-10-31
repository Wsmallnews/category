<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use UnitEnum;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

abstract class Category extends Page
{
    use Scopeable;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?int $level = null;

    public ?CategoryType $record = null;

    protected static ?string $title = '分类';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Bars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $navigationLabel = '分类管理';

    protected static string | UnitEnum | null $navigationGroup = '分类管理';

    protected static ?string $slug = 'categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected string $view = 'sn-category::filament.pages.category';

    public function mount(): void
    {
        $this->record = $this->getRecord();
    }

    public function getRecord(): ?CategoryType
    {
        $category = CategoryType::query()
            ->firstOrCreate(
                static::getScopeInfo(), 
                [
                    'name' => Str::title(static::getScopeType()),
                    'level' => $this->level ?? 1,
                    'status' => CategoryTypeStatus::Normal,
                ]
            );

        return $category;
    }
}
