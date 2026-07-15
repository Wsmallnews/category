<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes;

use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\CreateCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\EditCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\ListCategoryTypes;
use Wsmallnews\Support\Filament\Concerns\CanBeConfigured;
use Wsmallnews\Support\Filament\Resources\ResourceConfiguration;

final class CategoryTypeResource extends BaseResource
{
    use CanBeConfigured;

    protected static ?string $configurationClass = ResourceConfiguration::class;

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

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
