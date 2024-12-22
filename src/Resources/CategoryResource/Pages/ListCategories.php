<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Pages;

use Wsmallnews\Category\Resources\CategoryResource;
use Wsmallnews\Category\Resources\CategoryResource\Widgets\CategoryWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

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
            CategoryWidget::class
        ];
    }
}
