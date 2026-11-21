<?php

use Wsmallnews\Category\Models;

return [
    /**
     * Custom models
     */
    'models' => [
        'category' => Models\Category::class,
        'category_type' => Models\CategoryType::class,
    ],

    /**
     * 本包无站点：组件由 cms 或 panel 引入，scope 默认 [pluginId, 0]（即 sn-category），
     * 子 scope 用 'scope_suffix' 覆盖。
     *
     * 资源清单（默认注释，解开即注册；内存注入 resources([...]) 对同类覆盖）。
     */

    /**
     * 文件基础目录，会自动拼接当前年月日 (仅用于 filament 默认上传组件 (Forms\Components\FileUpload))
     */
    'file_directory' => 'sn/categories/',
];
