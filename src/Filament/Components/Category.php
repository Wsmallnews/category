<?php

namespace Wsmallnews\Category\Filament\Components;

use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Category\Models\Category as CategoryModel;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

class Category extends NestedsetPage
{
    // 所属类型
    public ?Model $categoryType;

    protected static ?string $model = CategoryModel::class;

    public function mount(): void
    {
        $this->level = $this->categoryType?->level ?: 1;

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


    public function getEmptyLabel(): ?string
    {
        return '组件的属性这里要解决';

        return static::getCustomProperty('emptyLabel') ?? parent::getEmptyLabel();
    }
}
