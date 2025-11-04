<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Components;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\Category\Models\Category as CategoryModel;
use Wsmallnews\Category\Models\CategoryType as CategoryTypeModel;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

class BaseCategory extends NestedsetPage
{
    // 所属类型
    public ?CategoryTypeModel $categoryType = null;

    public ?array $properties = [];

    protected static ?string $emptyLabel = '分类数据为空';

    protected static ?string $model = CategoryModel::class;

    protected static ?string $modelLabel = '分类管理';

    protected static ?string $pluralModelLabel = '分类管理';

    protected static ?string $title = '分类管理';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Bars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $navigationLabel = '分类管理';

    protected static string | UnitEnum | null $navigationGroup = '分类管理';

    protected static ?string $slug = 'categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;


    public function mount(): void
    {
        static::$level = $this->categoryType?->level;

        (isset($this->properties['emptyLabel']) && filled($this->properties['emptyLabel'])) && static::$emptyLabel = $this->properties['emptyLabel'];

        parent::mount();
    }

    public function createSchema($arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    public function editSchema($arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    public function infolistSchema(): array
    {
        return CategoryInfolist::infolist();
    }

    protected function nestedScoped()
    {
        return [
            'scope_type' => $this->categoryType?->scope_type,
            'scope_id' => $this->categoryType?->scope_id,
            'type_id' => $this->categoryType?->id,
        ];
    }

    protected function schema(array $arguments): array
    {
        return CategoryForm::forms($arguments);
    }
}
