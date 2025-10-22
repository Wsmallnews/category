<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryType extends CreateRecord
{
    protected static string $resource = CategoryTypeResource::class;
}
