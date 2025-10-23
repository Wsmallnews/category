<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Category\Filament\Widgets\Category;

class EditCategoryType extends EditRecord
{
    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }


    protected function getFooterWidgets(): array
    {
        return [
            Category::class,
        ];
    }
}
