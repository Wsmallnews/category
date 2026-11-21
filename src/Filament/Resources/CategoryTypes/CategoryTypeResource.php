<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes;

use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\CreateCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\EditCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\ListCategoryTypes;
use Wsmallnews\Support\Filament\Concerns\CanBeConfigured;

final class CategoryTypeResource extends BaseResource
{
    use CanBeConfigured;

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryTypes::route('/'),
            'create' => CreateCategoryType::route('/create'),
            'edit' => EditCategoryType::route('/{record}/edit'),
        ];
    }

    public static function getProperties(): array
    {
        return [
            'emptyLabel' => self::resolveCustomProperty('emptyLabel'),
        ];
    }
}
