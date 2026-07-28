<?php

declare(strict_types=1);

namespace Wsmallnews\Category\Support;

use Wsmallnews\Category\Exceptions\CategoryException;
use Wsmallnews\Category\Models;
use Wsmallnews\Support\Data\ScopeableContext;
use Wsmallnews\Support\Exceptions\InvalidScopeException;
use Wsmallnews\Support\Support\Utils as SupportUtils;

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
     * Get scopeable configuration as ScopeableContext object.
     *
     * @throws CategoryException
     */
    public static function getScopeableContext(): ScopeableContext
    {
        try {
            return SupportUtils::getScopeFromConfig('sn-category.scopeable');
        } catch (InvalidScopeException $e) {
            throw new CategoryException('Scopeable configuration error. ' . $e->getMessage());
        }
    }

    /**
     * Get scopeable array (legacy method for backward compatibility).
     *
     * @return array{scope_type: string, scope_id: int}
     *
     * @throws CategoryException
     */
    public static function getScopeable(): array
    {
        return self::getScopeableContext()->toArray();
    }

    /**
     * Get scope type.
     *
     * @throws CategoryException
     */
    public static function getScopeType(): string
    {
        return self::getScopeableContext()->scopeType;
    }

    /**
     * Get scope ID.
     *
     * @throws CategoryException
     */
    public static function getScopeId(): int
    {
        return self::getScopeableContext()->scopeId;
    }

    /**
     * Get panel register config.
     *
     * @param  string|null  $type  Register type (pages, resources, global_default) or null for all
     */
    public static function getPanelRegister(?string $type = null): mixed
    {
        if (blank($type)) {
            return self::getConfig('panel_register', null);
        }

        return self::getConfig("panel_register.$type", null);
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
