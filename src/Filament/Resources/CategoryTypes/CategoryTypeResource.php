<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes;

use BezhanSalleh\PluginEssentials\Concerns;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Concerns\Resource\HasCustomProperties;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\CreateCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\EditCategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Pages\ListCategoryTypes;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Tables\CategoryTypesTable;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Filament\Widgets\Category as CategoryWidgets;

class CategoryTypeResource extends Resource
{
    use Concerns\Resource\BelongsToParent;
    use Concerns\Resource\BelongsToTenant;
    use Concerns\Resource\HasGlobalSearch;
    use Concerns\Resource\HasLabels;
    use Concerns\Resource\HasNavigation;
    use HasCustomProperties;

    protected static ?string $model = CategoryType::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CategoryTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryTypesTable::configure($table);
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
            'index' => ListCategoryTypes::route('/'),
            'create' => CreateCategoryType::route('/create'),
            'edit' => EditCategoryType::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CategoryWidgets::class,
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEssentialsPlugin(): ?CategoryPlugin
    {
        return CategoryPlugin::get();
    }
}
