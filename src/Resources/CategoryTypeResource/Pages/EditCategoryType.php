<?php

namespace Wsmallnews\Category\Resources\CategoryTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Wsmallnews\Category\Resources\CategoryTypeResource;

class EditCategoryType extends EditRecord
{
    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
