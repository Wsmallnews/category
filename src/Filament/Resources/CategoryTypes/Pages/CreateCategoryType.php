<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Support\Filament\Resources\Concerns\Pages\Scopeable;

class CreateCategoryType extends CreateRecord
{
    use Scopeable;

    protected static string $resource = CategoryTypeResource::class;

    /**
     * Mutate the form data before creating a record.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 合并 scopeinfo 参数
        $data = array_merge($data, static::getResource()::getScopeInfo());

        return parent::mutateFormDataBeforeCreate($data);
    }
}
