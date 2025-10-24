<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;

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
