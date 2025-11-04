<?php

namespace Wsmallnews\Category\Filament\Pages;

use BezhanSalleh\PluginEssentials\Concerns;
use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Filament\Pages\Category\ManageBase as ManageBaseCategoryPage;
use Wsmallnews\Support\Concerns\Resource\HasCustomProperties;

final class ManageCategory extends ManageBaseCategoryPage
{
    use Concerns\Resource\BelongsToParent;
    use Concerns\Resource\BelongsToTenant;
    use Concerns\Resource\HasGlobalSearch;
    use Concerns\Resource\HasLabels;
    use Concerns\Resource\HasNavigation;
    use HasCustomProperties;

    public static function getScopeType(): string
    {
        return self::getCustomProperty('scopeType') ?? parent::getScopeType();
    }

    public static function getScopeId(): int
    {
        return self::getCustomProperty('scopeId') ?? parent::getScopeId();
    }

    public function getEmptyLabel(): ?string
    {
        return self::getCustomProperty('emptyLabel');
    }

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
