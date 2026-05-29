<?php

namespace Wsmallnews\Category;

use BezhanSalleh\PluginEssentials\Concerns\Plugin as Essentials;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Wsmallnews\Category\Filament\Pages\Category\CategoryPage;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Concerns\Plugin\HasCustomProperties;

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
        return 'sn-category';
    }

    public function register(Panel $panel): void
    {
        if (Utils::getPanelRegister('pages')) {
            $panel->pages([
                ...Utils::getPanelRegister('pages'),
            ]);
        }

        if (Utils::getPanelRegister('resources')) {
            $panel->resources([
                ...Utils::getPanelRegister('resources'),
            ]);
        }
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
                    'modelLabel' => __('sn-category::category.category_type_resource.model_label'),
                    'pluralModelLabel' => __('sn-category::category.category_type_resource.plural_model_label'),

                    'navigationGroup' => __('sn-category::category.category_type_resource.navigation_group'),
                    'navigationLabel' => __('sn-category::category.category_type_resource.navigation_label'),
                    'navigationIcon' => Heroicon::OutlinedBars3,
                    'activeNavigationIcon' => Heroicon::Bars3,
                    'navigationSort' => 1,
                    'navigationBadge' => null,
                    'navigationBadgeColor' => null,
                    'navigationParentItem' => null,
                    'registerNavigation' => true,

                    // hasGlobalSearch
                    'globallySearchable' => false,
                    'globalSearchResultsLimit' => 50,
                    'forceGlobalSearchCaseInsensitive' => null,
                    'splitGlobalSearchTerms' => false,

                    // belongsToParent
                    'parentResource' => null,

                    // HasCustomProperties
                    'customProperties' => [],
                ],
                CategoryPage::class => [
                    // hasLabels
                    'modelLabel' => __('sn-category::category.category_management.model_label'),
                    'pluralModelLabel' => __('sn-category::category.category_management.plural_model_label'),

                    // hasNavigation
                    'navigationLabel' => __('sn-category::category.category_management.navigation_label'),
                    'navigationIcon' => Heroicon::OutlinedChatBubbleLeft,
                    'activeNavigationIcon' => Heroicon::ChatBubbleLeft,
                    'navigationGroup' => __('sn-category::category.category_management.navigation_group'),
                    'navigationSort' => 1,
                    'navigationBadge' => null,
                    'navigationBadgeColor' => null,
                    'navigationParentItem' => null,
                    'registerNavigation' => true,

                    // hasGlobalSearch
                    'globallySearchable' => false,
                    'globalSearchResultsLimit' => 50,
                    'forceGlobalSearchCaseInsensitive' => null,
                    'splitGlobalSearchTerms' => false,

                    // belongsToParent
                    'parentResource' => null,

                    // HasCustomProperties
                    'customProperties' => [],
                ],
            ],
        ];
    }
}
