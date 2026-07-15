<?php

use Wsmallnews\Category\Filament\Pages\Category\CategoryPage;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
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

    /**
     * Panel register
     *
     * 支持两种格式：
     *   - 简单注册：ClassName::class（使用 Base 类中的硬编码默认值）
     *   - 带配置：ClassName::class => ['key' => 'value']（覆盖默认值）
     * 配置项键名使用驼峰转下划线（如 navigationIcon → navigation_icon）
     */
    'panel_register' => [
        'resources' => [
            CategoryTypeResource::class,
        ],
        'pages' => [
            CategoryPage::class,
        ],
    ],

    /**
     * 文件基础目录，会自动拼接当前年月日 (仅用于 filament 默认上传组件 (Forms\Components\FileUpload))
     */
    'file_directory' => 'sn/categories/',

];
