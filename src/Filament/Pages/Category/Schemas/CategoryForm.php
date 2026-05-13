<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Guava\IconPicker\Forms\Components\IconPicker;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Filament\Forms\FormComponents;

class CategoryForm
{
    public static function forms(array $arguments = []): array
    {
        return [
            Forms\Components\TextInput::make('name')->label(__('sn-category::category.category_form.name'))
                ->placeholder(__('sn-category::category.category_form.name_placeholder'))
                ->required(),
            Forms\Components\Textarea::make('description')->label(__('sn-category::category.category_form.description')),
            Forms\Components\ToggleButtons::make('options.icon_type')
                ->label(__('sn-category::category.category_form.icon_type'))
                ->options([
                    'none' => __('sn-category::category.category_form.icon_type_none'),
                    'icon' => __('sn-category::category.category_form.icon_type_icon'),
                    'image' => __('sn-category::category.category_form.icon_type_image'),
                ])
                ->default('none')
                ->inline(),
            Schemas\Components\Fieldset::make('icons')
                ->label(__('sn-category::category.category_form.icon_fieldset'))
                ->schema([
                    IconPicker::make('options.icon')
                        ->label(__('sn-category::category.category_form.icon'))
                        ->placeholder(__('sn-category::category.category_form.icon_placeholder')),
                    IconPicker::make('options.active_icon')
                        ->label(__('sn-category::category.category_form.active_icon'))
                        ->placeholder(__('sn-category::category.category_form.active_icon_placeholder')),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'icon'
                JS),
            Schemas\Components\Fieldset::make('image_icons')
                ->label(__('sn-category::category.category_form.image_fieldset'))
                ->schema([
                    FormComponents::localImageUpload('options.icon_src')
                        ->label(__('sn-category::category.category_form.image_icon'))
                        ->directory(Utils::getFileDirectory('icons'))
                        ->automaticallyResizeImagesMode('cover')
                        ->imageAspectRatio('1:1')
                        ->automaticallyCropImagesToAspectRatio()
                        ->automaticallyResizeImagesToHeight('200')
                        ->automaticallyResizeImagesToWidth('200')
                        ->uploadingMessage(__('sn-category::category.category_form.icon_uploading')),
                    FormComponents::localImageUpload('options.active_icon_src')
                        ->label(__('sn-category::category.category_form.image_active_icon'))
                        ->directory(Utils::getFileDirectory('icons'))
                        ->automaticallyResizeImagesMode('cover')
                        ->imageAspectRatio('1:1')
                        ->automaticallyCropImagesToAspectRatio()
                        ->automaticallyResizeImagesToHeight('200')
                        ->automaticallyResizeImagesToWidth('200')
                        ->uploadingMessage(__('sn-category::category.category_form.active_icon_uploading')),
                    Schemas\Components\Text::make(__('sn-category::category.category_form.image_hint'))
                        ->columnSpanFull(),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'image'
                JS),

            Forms\Components\Radio::make('status')
                ->label(__('sn-category::category.category_form.status'))
                ->default(CategoryStatus::Normal)
                ->inline()
                ->options(CategoryStatus::class)
                ->columnSpan(1),
        ];
    }
}
