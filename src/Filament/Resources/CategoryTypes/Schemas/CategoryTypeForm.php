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
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')->schema([
                            Forms\Components\TextInput::make('name')->label('类别名称')
                                ->placeholder('请输入类别名称')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\Radio::make('level')->label('层级')
                                ->options([
                                    1 => '一级',
                                    2 => '二级',
                                    3 => '三级',
                                ])
                                ->default(1)
                                ->inline()
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('description')->label('类别描述')
                                ->placeholder('请输入类别描述')
                                ->columnSpan(1),
                        ])->columns(2),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('状态')
                            ->default(CategoryTypeStatus::Normal)
                            ->inline()
                            ->options(CategoryTypeStatus::class),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('lg'),
            ]);
    }
}
