<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryTypes extends ListRecords
{
    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
