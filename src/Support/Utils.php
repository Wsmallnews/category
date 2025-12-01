<?php

declare(strict_types=1);

namespace Wsmallnews\Category\Support;

use Wsmallnews\Category\Exceptions\CategoryException;
use Wsmallnews\Category\Models;

class Utils
{
    public static function getConfig($name = null, $default = null)
    {
        $config = config('sn-category');

        return $name ? (data_get($config, $name) ?? $default) : $config;
    }

    /**
     * 获取 scopeinfo 参数
     *
     * @throws CategoryException
     */
    public static function getScopeable(): array
    {
        $scopeable = self::getConfig('scopeable');
        if (
            ! isset($scopeable['scope_type']) || blank($scopeable['scope_type'])
            || ! isset($scopeable['scope_id']) || blank($scopeable['scope_id'])
        ) {
            throw new CategoryException('scopeable配置错误, 请检查 sn-cms.php 配置文件');
        }

        return $scopeable;
    }

    /**
     * 获取 scopeType 参数
     *
     * @throws CategoryException
     */
    public static function getScopeType(): string
    {
        return self::getScopeable()['scope_type'];
    }

    /**
     * 获取 scopeId 参数
     *
     * @throws CategoryException
     */
    public static function getScopeId(): int
    {
        return self::getScopeable()['scope_id'];
    }

    /**
     * 获取模型
     */
    public static function getModel(string $name, bool $shouldException = true): ?string
    {
        $model = self::getConfig('models')[$name] ?? null;

        if (blank($model) && $shouldException) {
            throw new CategoryException("模型 {$name} 不存在");
        }

        return $model;
    }

    /**
     * 获取 分类 model
     *
     * @return Models\Category
     */
    public static function getCategoryModel(): string
    {
        return self::getModel('category');
    }

    /**
     * 获取分类类型 model
     *
     * @return Models\CategoryType
     */
    public static function getCategoryTypeModel(): string
    {
        return self::getModel('category_type');
    }

    /**
     * 获取文件目录
     *
     * @param  string|null  $type  目录类型
     * @return string
     */
    public static function getFileDirectory($type = null)
    {
        return self::getConfig('file_directory', 'sn/categories/') . ($type ? $type . '/' : '') . date('Ymd');
    }
}
