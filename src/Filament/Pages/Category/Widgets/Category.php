<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Widgets;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Attributes\Reactive;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\FilamentNestedset\Filament\Pages\Widgets\Nestedset;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

class Category extends Nestedset
{
    use Scopeable;

    #[Reactive]
    public ?CategoryType $record = null;

    public static function getModel(): ?string
    {
        return Utils::getCategoryModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.category_page.model_label');
    }

    public static function getRecordLabel(Model $record): HtmlString | string
    {
        return $record->name_label;
    }

    public static function nestedScoped(): array
    {
        $categoryType = static::getCategoryType();

        return [
            'scope_type' => $categoryType?->scope_type,
            'scope_id' => $categoryType?->scope_id,
            'type_id' => $categoryType?->id,
        ];
    }

    public static function schema(array $arguments): array
    {
        return CategoryForm::forms($arguments);
    }

    public static function infolistSchema(): array
    {
        return CategoryInfolist::infolist();
    }

    public static function getCategoryType(): ?CategoryType
    {
        $categoryType = Utils::getCategoryTypeModel()::query()
            ->snScope(static::getScopeType(), static::getScopeId())
            ->first();

        if (! $categoryType && ! static::getCanManage()) {
            // 自动创建分类类型
            $categoryType = Utils::getCategoryTypeModel()::create([
                'name' => Str::title(static::getScopeType()),
                'level' => static::getLevel(),
                'status' => CategoryTypeStatus::Normal,
                ...static::getScopeable(),
                'team_id' => Filament::getTenant()?->id,
            ]);
        }

        return $categoryType;
    }
}
