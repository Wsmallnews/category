<?php

declare(strict_types=1);

namespace Wsmallnews\Category\Support;

use Wsmallnews\Category\Exceptions\CategoryException;

/**
 * Utility class for Category package configuration and helpers.
 */
class Utils
{
    /**
     * Get configuration value.
     *
     * @param  string|null  $name  Configuration key (dot notation)
     * @param  mixed  $default  Default value if not found
     */
    public static function getConfig(?string $name = null, mixed $default = null): mixed
    {
        $config = config('sn-category');

        return $name ? (data_get($config, $name) ?? $default) : $config;
    }

    /**
     * Get model class by name.
     *
     * @param  string  $name  Model name (e.g., 'post', 'navigation')
     * @param  bool  $shouldException  Whether to throw exception if not found
     *
     * @throws CategoryException
     */
    public static function getModel(string $name, bool $shouldException = true): ?string
    {
        $model = self::getConfig('models')[$name] ?? null;

        if (blank($model) && $shouldException) {
            throw new CategoryException("Model {$name} not found.");
        }

        return $model;
    }

    /**
     * Get category model class.
     *
     * @return string Models\Category
     */
    public static function getCategoryModel(): string
    {
        return self::getModel('category');
    }

    /**
     * Get category type model class.
     *
     * @return string Models\CategoryType
     */
    public static function getCategoryTypeModel(): string
    {
        return self::getModel('category_type');
    }

    /**
     * Get file directory path with optional type and date.
     *
     * @param  string|null  $type  Directory type
     */
    public static function getFileDirectory(?string $type = null): string
    {
        return self::getConfig('file_directory', 'sn/categories/') . ($type ? $type . '/' : '') . date('Ymd');
    }
}
