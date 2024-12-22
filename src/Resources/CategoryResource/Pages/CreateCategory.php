<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Pages;

use Wsmallnews\Category\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
