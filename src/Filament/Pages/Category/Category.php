<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Wsmallnews\Category\Models\CategoryType;

abstract class Category extends Page
{
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
            ->where('scope_type', 'default_shop')
            ->where('scope_id', 8)
            ->firstOrCreate();
        
        return $category;
    }
}
