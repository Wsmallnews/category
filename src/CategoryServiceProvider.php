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
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wsmallnews\Category\Commands\CategoryCommand;
use Wsmallnews\Category\Models\Category as CategoryModel;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Testing\TestsCategory;

class CategoryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'sn-category';

    public static string $viewNamespace = 'sn-category';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('wsmallnews/category');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
            $package->runsMigrations();
        }

        // if (file_exists($package->basePath('/../resources/lang'))) {
        //     $package->hasTranslations();
        // }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }

    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // 注册模型别名
        Relation::enforceMorphMap([
            'sn_category' => CategoryModel::class,
            'sn_category_type' => CategoryType::class,
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
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/category/{$file->getFilename()}"),
                ], 'category-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsCategory);
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
            CategoryCommand::class,
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
            '2025_01_20_113233_create_sn_categories_table',
            '2025_01_20_113233_create_sn_category_types_table'
        ];
    }
}
