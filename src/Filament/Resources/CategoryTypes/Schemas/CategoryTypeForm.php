<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Wsmallnews\Category\Enums\CategoryTypeStatus;

class CategoryTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...static::forms(),
            ]);
    }

    public static function forms(): array
    {
        return [
            Schemas\Components\Flex::make([
                Schemas\Components\Group::make()->schema([
                    Schemas\Components\Section::make(__('sn-category::category.category_type_form.basic_info'))->schema([
                        Forms\Components\TextInput::make('name')->label(__('sn-category::category.category_type_form.name'))
                            ->placeholder(__('sn-category::category.category_type_form.name_placeholder'))
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Radio::make('level')->label(__('sn-category::category.category_type_form.level'))
                            ->options([
                                1 => __('sn-category::category.category_type_form.level_one'),
                                2 => __('sn-category::category.category_type_form.level_two'),
                                3 => __('sn-category::category.category_type_form.level_three'),
                                'infinite' => __('sn-category::category.category_type_form.level_infinite'),
                            ])
                            ->formatStateUsing(fn ($state) => is_null($state) ? 'infinite' : $state)     // 显示时，null 转成 infinite
                            ->dehydrateStateUsing(fn ($state) => $state === 'infinite' ? null : $state)  // 存库时，infinite 转成 null
                            ->default(1)
                            ->inline()
                            ->required()
                            ->helperText(fn ($operation) => $operation === 'create' ? __('sn-category::category.category_type_form.level_helper_create') : __('sn-category::category.category_type_form.level_helper_edit'))
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('description')->label(__('sn-category::category.category_type_form.description'))
                            ->placeholder(__('sn-category::category.category_type_form.description_placeholder'))
                            ->columnSpan(1),
                    ])->columns(2),
                ])->columns(1),
                Schemas\Components\Section::make(__('sn-category::category.category_type_form.status_section'))->schema([
                    Forms\Components\TextInput::make('order_column')->label(__('sn-category::category.category_type_form.order_column'))->integer()
                        ->placeholder(__('sn-category::category.category_type_form.order_column_placeholder'))
                        ->rules(['integer', 'min:0']),
                    Forms\Components\Radio::make('status')
                        ->label(__('sn-category::category.category_type_form.status'))
                        ->default(CategoryTypeStatus::Normal)
                        ->inline()
                        ->options(CategoryTypeStatus::class),
                ])->grow(false),
            ])
                ->columnSpanFull()
                ->from('lg'),
        ];
    }
}
