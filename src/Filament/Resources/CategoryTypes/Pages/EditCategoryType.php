<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Wsmallnews\Category\Filament\Pages\Category\Widgets\CategoryManage as CategoryManageWidgets;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Support\Filament\Resources\Concerns\Pages\Scopeable;

class EditCategoryType extends EditRecord
{
    use Scopeable;

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
        $record = $this->getRecord();

        return [
            CategoryManageWidgets::make([
                'properties' => static::getResource()::getProperties() ?? [],
                'key' => 'widgets-' . $record?->id . '-' . $record?->level,
            ]),
        ];
    }
}
