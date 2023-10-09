<?php

namespace Wsmallnews\Category\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wsmallnews\Category\Category
 */
class Category extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wsmallnews\Category\Category::class;
    }
}
