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

class Category extends Nestedset
{
    #[Reactive]
    public ?CategoryType $record = null;

    public function boot()
    {
        static::$level = $this->record?->level ?? null;
    }

    public static function getModel(): ?string
    {
        return Utils::getCategoryModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.category_page.model_label');
    }

    public function getRecordLabel(Model $record): HtmlString | string
    {
        return $record->name_label;
    }

    public function nestedScoped(): array
    {
        return [
            'scope_type' => $this->record?->scope_type,
            'scope_id' => $this->record?->scope_id,
            'type_id' => $this->record?->id,
        ];
    }

    public function schema(array $arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return CategoryForm::forms($arguments);
    }

    public function infolistSchema(): array
    {
        return CategoryInfolist::infolist();
    }
}
