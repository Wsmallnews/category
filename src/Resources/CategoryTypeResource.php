<?php

namespace Wsmallnews\Category\Resources;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Resources\CategoryTypeResource\Pages;
use Wsmallnews\Support\Filament\Resources\SupportResource;

class CategoryTypeResource extends SupportResource
{
    protected static ?string $model = CategoryType::class;

    protected static ?string $navigationGroup = '分类管理';

    protected static ?string $navigationLabel = '类别管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '类别';

    protected static ?string $pluralModelLabel = '类别管理';

    protected static ?string $slug = '/categories/types';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\TextInput::make('name')->label('类别名称')
                            ->placeholder('请输入类别名称')
                            ->required()
                            ->columnSpan(1),
                        Components\TextInput::make('description')->label('类别描述')
                            ->placeholder('请输入类别描述')
                            ->columnSpan(1),
                        Components\Radio::make('level')->label('层级')
                            ->options([
                                1 => '一级',
                                2 => '二级',
                                3 => '三级',
                            ])
                            ->default(1)
                            ->inline()
                            ->required()
                            ->columnSpan(1),
                        Components\Radio::make('status')->label('状态')
                            ->options(Enums\CategoryTypeStatus::class)
                            ->default(Enums\CategoryTypeStatus::Normal->value)
                            ->inline()
                            ->required()
                            ->columnSpan(1),
                        Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0'])
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => fn (?CategoryType $record) => $record === null ? 3 : 2]),
                Components\Section::make()
                    ->schema([
                        Components\Placeholder::make('created_at')
                            ->label('创建时间')
                            ->content(fn (CategoryType $record): ?string => $record->created_at?->diffForHumans()),
                        Components\Placeholder::make('updated_at')
                            ->label('更新时间')
                            ->content(fn (CategoryType $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columns(1)
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?CategoryType $record): bool => is_null($record)),

                // Components\Section::make()
                //     ->schema([
                //         Components\Grid::make()
                //             ->schema([
                //                 Components\TextInput::make('name')->label('类别名称')
                //                     ->placeholder('请输入类别名称')->helperText('分类类别名称')->required(),
                //                 Components\TextInput::make('type_identify')->label('类别标识')
                //                     ->alpha()
                //                     ->placeholder('请输入类别标识')->helperText('必须是全英文字母')->required(),
                //             ]),
                //         Components\Radio::make('level')->label('层级')
                //             ->options([
                //                 1 => '一级',
                //                 2 => '二级',
                //                 3 => '三级',
                //             ])
                //             ->default(1)
                //             ->inline()
                //             ->required(),

                //         Components\Textarea::make('description')->label('描述')
                //             ->placeholder('请输入类别描述'),

                //         Components\Radio::make('status')->label('状态')
                //             ->options(Enums\CategoryTypeStatus::class)
                //             ->default(Enums\CategoryTypeStatus::Normal->value)
                //             ->inline()
                //             ->required()
                //     ])
                //     ->columnSpan(['lg' => fn (?SnCategoryType $record) => $record === null ? 3 : 2]),
                // Components\Section::make()
                //     ->schema([
                //         Components\Placeholder::make('created_at')
                //             ->label('创建时间')
                //             ->content(fn (SnCategoryType $record): ?string => $record->created_at?->diffForHumans()),
                //         Components\Placeholder::make('updated_at')
                //             ->label('更新时间')
                //             ->content(fn(SnCategoryType $record): ?string => $record->updated_at?->diffForHumans())
                //     ])
                //     ->columnSpan(['lg' => 1])
                //     ->hidden(fn(?SnCategoryType $record): bool => is_null($record))
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('类别名称')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type_identify')
                    ->label('类别标识')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('层级')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('状态')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategoryTypes::route('/'),
            'create' => Pages\CreateCategoryType::route('/create'),
            'edit' => Pages\EditCategoryType::route('/{record}/edit'),
        ];
    }
}
