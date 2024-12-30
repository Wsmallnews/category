<?php

namespace Wsmallnews\Category;

use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Traits\Resources\CanSetResource;

class Category
{
    use CanSetResource;

    public static $image_directory;

    public static function setImageDirectory($image_directory)
    {
        self::$image_directory = $image_directory;
    }

    public static function getImageDirectory()
    {
        return self::$image_directory ?: 'filaments/categories/' . date('Ymd');
    }

    public static function registerType($scope_type)
    {
        $categoryType = new CategoryType;
        $categoryType->scope_type = $scope_type;
        $categoryType->scope_id = 0;
        $categoryType->name = $scope_type;
        $categoryType->level = 1;
        $categoryType->status = Enums\CategoryTypeStatus::Normal;

        $categoryType->save();
    }
}
