<?php

namespace Wsmallnews\Category;

use BezhanSalleh\PluginEssentials\Concerns\Plugin as Essentials;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Wsmallnews\Category\Concerns\Plugin\HasCustomProperties;
use Wsmallnews\Category\Filament\Pages\Category;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;

class CategoryPlugin implements Plugin
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasGlobalSearch;
    use Essentials\HasLabels;
    use Essentials\HasNavigation;
    use Essentials\HasPluginDefaults;
    use Essentials\WithMultipleResourceSupport;
    use EvaluatesClosures;
    use HasCustomProperties;

    public function getId(): string
    {
        return 'sn_category';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            CategoryTypeResource::class,
        ])->pages([
            Category::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * 资源默认值
     */
    protected function getPluginDefaults(): array
    {
        return [
            'resources' => [
                CategoryTypeResource::class => [
                    'modelLabel' => '分类类型',
                    'pluralModelLabel' => '分类类型',

                    'navigationGroup' => '分类管理',
                    'navigationLabel' => '分类类型',
                    'navigationIcon' => Heroicon::Bars3,
                    'activeNavigationIcon' => Heroicon::Bars3,
                    'navigationSort' => 1,
                    'navigationBadge' => null,
                    'navigationBadgeColor' => null,
                    'navigationParentItem' => null,
                    'registerNavigation' => true,

                    'globalSearchResultsLimit' => 50,
                ],
                Category::class => [
                    'modelLabel' => '分类',

                    'navigationGroup' => '分类管理',
                    'navigationLabel' => '分类类型',
                    'navigationIcon' => Heroicon::Bars3BottomLeft,
                    'activeNavigationIcon' => Heroicon::Bars3BottomLeft,
                    'navigationSort' => 1,
                    'navigationBadge' => null,
                    'navigationBadgeColor' => null,
                    'navigationParentItem' => null,
                    'registerNavigation' => true,

                    'recordTitleAttribute' => 'name',

                    'customProperties' => [
                        'title' => '分类',
                        'emptyLabel' => '分类数据为空',
                    ],
                ],
            ],
        ];
    }
}
