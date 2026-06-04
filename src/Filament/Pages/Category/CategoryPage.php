<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BezhanSalleh\PluginEssentials\Concerns;
use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Concerns\Resource\HasCustomProperties;

final class CategoryPage extends Base
{
    use Concerns\Resource\BelongsToParent;
    use Concerns\Resource\BelongsToTenant;
    use Concerns\Resource\HasGlobalSearch;
    use Concerns\Resource\HasLabels;
    use Concerns\Resource\HasNavigation;
    use HasCustomProperties;

    public static function getScopeType(): string
    {
        return self::getCustomScopeType() ?? Utils::getScopeType();
    }

    public static function getScopeId(): int
    {
        return self::getCustomScopeId() ?? Utils::getScopeId();
    }

    public static function getLevel(): ?int
    {
        if (self::getCanManage()) {
            // 可管理导航类型，则使用父级 getLevel 方法
            return parent::getLevel();
        }

        // 固定层级
        return self::getCustomProperty('level') ?? parent::getLevel();
    }

    public static function getCanManage(): bool
    {
        return self::getCustomProperty('canManage', false);
    }

    public static function getEmptyLabel(): ?string
    {
        return self::getCustomProperty('emptyLabel') ?? parent::getEmptyLabel();
    }

    public static function getEmptyTipLabel(): ?string
    {
        return self::getCustomProperty('emptyTipLabel') ?? parent::getEmptyTipLabel();
    }

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
