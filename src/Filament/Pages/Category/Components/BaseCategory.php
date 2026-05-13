<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Components;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use UnitEnum;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\Category\Models\CategoryType as CategoryTypeModel;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

class BaseCategory extends NestedsetPage
{
    public ?CategoryTypeModel $categoryType = null;

    public ?array $properties = [];

    protected static ?string $emptyLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $title = null;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Bars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $navigationLabel = null;

    protected static string | UnitEnum | null $navigationGroup = null;

    protected static ?string $slug = 'categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getModel(): ?string
    {
        return Utils::getCategoryModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.category_management.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('sn-category::category.category_management.plural_model_label');
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return static::$title ?? __('sn-category::category.category_management.title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('sn-category::category.category_management.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return static::$navigationGroup ?? __('sn-category::category.category_management.navigation_group');
    }

    public function getEmptyLabel(): ?string
    {
        return (isset($this->properties['emptyLabel']) && filled($this->properties['emptyLabel'])) ? $this->properties['emptyLabel'] : (static::$emptyLabel ?? __('sn-category::category.category_management.empty_label'));
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

    public function getRecordLabel(Model $category): HtmlString | string
    {
        return $category->name_label;
    }

    public function getLevel(): ?int
    {
        return $this->categoryType?->level;
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
