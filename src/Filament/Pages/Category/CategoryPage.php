<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Support\Filament\Concerns\CanBeConfigured;
use Wsmallnews\Support\Filament\Pages\PageConfiguration;

final class CategoryPage extends Base
{
    use CanBeConfigured;

    protected static ?string $configurationClass = PageConfiguration::class;

    public static function getLevel(): ?int
    {
        if (static::getCanManage()) {
            // 可管理导航类型，则使用父级 getLevel 方法
            return parent::getLevel();
        }

        return static::resolveCustomProperty('level') ?? parent::getLevel();
    }

    public static function getCanManage(): bool
    {
        return static::resolveCustomProperty('canManage') ?? false;
    }

    public static function getEmptyLabel(): ?string
    {
        return static::resolveCustomProperty('emptyLabel') ?? parent::getEmptyLabel();
    }

    public static function getEmptyTipLabel(): ?string
    {
        return static::resolveCustomProperty('emptyTipLabel') ?? parent::getEmptyTipLabel();
    }

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
