<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Wsmallnews\Category\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
