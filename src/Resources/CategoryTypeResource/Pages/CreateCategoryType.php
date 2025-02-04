<?php

namespace Wsmallnews\Category\Resources\CategoryTypeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Wsmallnews\Category\Resources\CategoryTypeResource;
use Wsmallnews\Support\Traits\Resources\Pages\CanScopeable;

class CreateCategoryType extends CreateRecord
{
    use CanScopeable;

    protected static string $resource = CategoryTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->fillScopeable($data);

        return $data;
    }
}
