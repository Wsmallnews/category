<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;

class CreateCategoryType extends CreateRecord
{
    protected static string $resource = CategoryTypeResource::class;
}
