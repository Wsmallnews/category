<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Wsmallnews\Category\Resources\CategoryResource;
use Wsmallnews\Category\Resources\CategoryResource\Widgets\CategoryWidget;

class ListCategories extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CategoryWidget::class,
        ];
    }
}
