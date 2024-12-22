<?php

namespace Wsmallnews\Category\Resources\CategoryTypeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Wsmallnews\Category\Resources\CategoryTypeResource;

class CreateCategoryType extends CreateRecord
{
    protected static string $resource = CategoryTypeResource::class;
}
