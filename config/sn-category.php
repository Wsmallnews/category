<?php

use Wsmallnews\Category\Models;

return [
    /**
     * Default scopeable
     */
    'scopeable' => [
        'scope_type' => 'sn-category',
        'scope_id' => 0,
    ],

    /**
     * Custom models
     */
    'models' => [
        'category' => Models\Category::class,
        'category_type' => Models\CategoryType::class,
    ],

];
