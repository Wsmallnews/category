<?php

namespace Wsmallnews\Category;

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


}
