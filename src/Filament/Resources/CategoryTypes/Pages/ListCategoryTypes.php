<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Support\Filament\Resources\Concerns\Pages\ForwardsResourceScope;

class ListCategoryTypes extends ListRecords
{
    use ForwardsResourceScope;

    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
