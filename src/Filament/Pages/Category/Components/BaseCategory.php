<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Components;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
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

    public function getRecordLabel(Model $item): HtmlString | string
    {
        $recordLabel = '';
        $icon_type = $item->options['icon_type'] ?? 'none';
        if ($icon_type == 'icon') {
            $icon = $item->options['icon'] ?? ($item->options['active_icon'] ?? '');
            $icon && $recordLabel = "<x-filament::icon icon=\"{$icon}\" class=\"size-6 mr-2\" />";
        } else if ($icon_type == 'image') {
            $image = $item->options['icon_src'] ?? ($item->options['active_icon_src'] ?? '');
            $image && $recordLabel = "<img src=\"" . files_url($image) . "\" class=\"size-6 mr-2\" />";
        }

        $recordLabel = $recordLabel . parent::getRecordLabel($item);

        return new HtmlString($recordLabel);
    }

    public function getLevel(): ?int
    {
        return $this->categoryType?->level;
    }

    public function getEmptyLabel(): ?string
    {
        return (isset($this->properties['emptyLabel']) && filled($this->properties['emptyLabel'])) ? $this->properties['emptyLabel'] : parent::getEmptyLabel();
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
