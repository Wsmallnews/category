<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Wsmallnews\Support\Helpers\FilamentHelper;

class CategoryTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('sn-category::category.category_type_table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('sn-category::category.category_type_table.level'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('sn-category::category.category_type_table.description'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order_column')
                    ->label(__('sn-category::category.category_type_table.order_column'))
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('sn-category::category.category_type_table.status'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('sn-category::category.category_type_table.created_at'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('sn-category::category.category_type_table.updated_at'))
                    ->toggleable()
                    ->sortable(),
            ])
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder(__('sn-category::category.category_type_table.search_placeholder'))
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...FilamentHelper::createUpdateRangeFilter(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            $records->each(function ($record) {
                                // 强制删除时，先删除关联的分类
                                $record->categories()->delete();
                            });
                        }),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
