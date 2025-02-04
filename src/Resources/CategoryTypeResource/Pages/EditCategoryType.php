<?php

namespace Wsmallnews\Category\Resources\CategoryTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Wsmallnews\Category\Resources\CategoryTypeResource;
use Wsmallnews\Support\Traits\Resources\Pages\CanScopeable;

class EditCategoryType extends EditRecord
{
    use CanScopeable;

    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
