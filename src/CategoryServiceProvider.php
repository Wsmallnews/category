<?php

namespace Wsmallnews\Category;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wsmallnews\Category\Commands\CategoryInstallCommand;
use Wsmallnews\Category\Livewire\Components\Categories as CategoriesComponent;
use Wsmallnews\Category\Support\Utils;

class CategoryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'sn-category';

    public static string $viewNamespace = 'sn-category';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasConfigFile()
            ->hasMigrations($this->getMigrations())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // 注册模型别名
        Relation::enforceMorphMap([
            'sn_category' => Utils::getCategoryModel(),
            'sn_category_type' => Utils::getCategoryTypeModel(),
        ]);

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/category/{$file->getFilename()}"),
                ], 'category-stubs');
            }
        }

        // 注册 livewire 组件
        Livewire::component('sn-category-components-categories', CategoriesComponent::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'wsmallnews/category';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('category', __DIR__ . '/../resources/dist/components/category.js'),
            // Css::make('category-styles', __DIR__ . '/../resources/dist/category.css'),
            // Js::make('category-scripts', __DIR__ . '/../resources/dist/category.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            CategoryInstallCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_sn_categories_table',
            'create_sn_category_types_table',
        ];
    }
}
