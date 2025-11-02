<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes;

use BezhanSalleh\PluginEssentials\Concerns;
use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\CreateCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\EditCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\ListCategoryTypes;
use Wsmallnews\Support\Concerns\Resource\HasCustomProperties;

final class CategoryTypeResource extends BaseResource
{
    use Concerns\Resource\BelongsToParent;
    use Concerns\Resource\BelongsToTenant;
    use Concerns\Resource\HasGlobalSearch;
    use Concerns\Resource\HasLabels;
    use Concerns\Resource\HasNavigation;
    use HasCustomProperties;

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryTypes::route('/'),
            'create' => CreateCategoryType::route('/create'),
            'edit' => EditCategoryType::route('/{record}/edit'),
        ];
    }

    public static function getScopeType(): string
    {
        return self::getCustomProperty('scopeType') ?? parent::getScopeType();
    }

    public static function getScopeId(): int
    {
        return self::getCustomProperty('scopeId') ?? parent::getScopeId();
    }

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
