<?php

namespace Wsmallnews\Category;

use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Traits\Resources\CanSetResource;

class Category
{
    use CanSetResource;

    public static $imageDirectory;

    public static function setImageDirectory($imageDirectory)
    {
        self::$imageDirectory = $imageDirectory;
    }

    public static function getImageDirectory()
    {
        return self::$imageDirectory ?: 'filaments/categories/' . date('Ymd');
    }

    public static function registerType($scope_type)
    {
        $key = 'sn-category:' . 'register-type:' . $scope_type;
        $exists = through_cache($key, function () use ($scope_type) {
            $exists = CategoryType::scopeable($scope_type)->exists();

            return $exists;
        }, ttl: now()->addDay());

        if (! $exists) {
            $categoryType = new CategoryType;
            $categoryType->scope_type = $scope_type;
            $categoryType->scope_id = 0;
            $categoryType->name = $scope_type;
            $categoryType->level = 1;
            $categoryType->status = Enums\CategoryTypeStatus::Normal;

            $categoryType->save();
        }
    }
}
