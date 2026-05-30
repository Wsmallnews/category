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
            'navigationGroup' => fn () => __('sn-category::category.global_default.navigation_group'),
            'globallySearchable' => false,
            'globalSearchResultsLimit' => 25,

            'resources' => [
                CategoryTypeResource::class => [
                    'modelLabel' => fn () => __('sn-category::category.category_type_resource.model_label'),
                    'pluralModelLabel' => fn () => __('sn-category::category.category_type_resource.plural_model_label'),

                    'navigationLabel' => fn () => __('sn-category::category.category_type_resource.navigation_label'),
                    'navigationIcon' => Heroicon::OutlinedBars3,
                    'activeNavigationIcon' => Heroicon::Bars3,
                    'navigationSort' => 1,
                ],
                CategoryPage::class => [
                    'modelLabel' => fn () => __('sn-category::category.category_page.model_label'),
                    'pluralModelLabel' => fn () => __('sn-category::category.category_page.plural_model_label'),

                    'navigationLabel' => fn () => __('sn-category::category.category_page.navigation_label'),
                    'navigationIcon' => Heroicon::OutlinedChatBubbleLeft,
                    'activeNavigationIcon' => Heroicon::ChatBubbleLeft,
                    'navigationSort' => 1,
                ],
            ],
        ];
    }
}
