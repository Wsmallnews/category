<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Widgets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Reactive;
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

    protected static ?CategoryType $categoryType = null;

    public function mount(): void
    {
        parent::mount();

        static::$categoryType = $this->record;
    }

    public static function getModel(): ?string
    {
        return Utils::getCategoryModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.category_page.model_label');
    }

    public static function getRecordLabel(Model $record): HtmlString|string
    {
        return $record->name_label;
    }

    public static function nestedScoped(): array
    {
        return [
            'scope_type' => static::$categoryType?->scope_type,
            'scope_id' => static::$categoryType?->scope_id,
            'type_id' => static::$categoryType?->id,
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
}
