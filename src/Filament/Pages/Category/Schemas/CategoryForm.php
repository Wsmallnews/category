<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Guava\IconPicker\Forms\Components\IconPicker;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Support\Utils as SupportUtils;

class CategoryForm
{
    public static function forms(array $arguments = []): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('description')->label('描述'),
            Forms\Components\ToggleButtons::make('options.icon_type')
                ->label('分类图标')
                ->options([
                    'none' => '无图标',
                    'icon' => 'icon图标',
                    'image' => '图片图标',
                ])
                ->default('none')
                ->inline(),
            Schemas\Components\FieldSet::make('icons')
                ->label('icon 图标')
                ->schema([
                    IconPicker::make('options.icon')
                        ->label('图标')
                        ->placeholder('请选择图标'),
                    IconPicker::make('options.active_icon')
                        ->label('活动图标')
                        ->placeholder('请选择活动图标'),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'icon'
                JS),
            Schemas\Components\FieldSet::make('image_icons')
                ->label('图片图标')
                ->schema([
                    Forms\Components\FileUpload::make('options.icon_src')
                        ->label('图标')
                        ->image()
                        ->disk(SupportUtils::getFilesystemDisk())
                        ->directory(Utils::getFileDirectory('icons'))
                        ->visibility('public')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetHeight('200')
                        ->imageResizeTargetWidth('200')
                        ->openable()
                        ->downloadable()
                        ->uploadingMessage('图标上传中...')
                        ->imagePreviewHeight('100'),
                    Forms\Components\FileUpload::make('options.active_icon_src')
                        ->label('活动图标')
                        ->image()
                        ->disk(SupportUtils::getFilesystemDisk())
                        ->directory(Utils::getFileDirectory('icons'))
                        ->visibility('public')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetHeight('200')
                        ->imageResizeTargetWidth('200')
                        ->openable()
                        ->downloadable()
                        ->uploadingMessage('活动图标上传中...')
                        ->imagePreviewHeight('100'),
                    Schemas\Components\Text::make('请上传正方形图片，推荐大小为 200x200 像素，非正方形图片将被自动缩放裁剪')
                        ->columnSpanFull(),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'image'
                JS),

            Forms\Components\Radio::make('status')
                ->label('状态')
                ->default(CategoryStatus::Normal)
                ->inline()
                ->options(CategoryStatus::class)
                ->columnSpan(1),
        ];
    }
}
