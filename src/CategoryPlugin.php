<?php

namespace Wsmallnews\Category;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Wsmallnews\Category\Resources\CategoryResource;
use Wsmallnews\Category\Resources\CategoryTypeResource;
use Wsmallnews\Category\Resources\Pages\Category;

class CategoryPlugin implements Plugin
{
    public function getId(): string
    {
        return 'sn_category';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                CategoryTypeResource::class,
                // CategoryResource::class,
                // PostResource::class,
                // CategoryResource::class,
            ])
            ->pages([
                Category::class,
                // Settings::class,
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
}
