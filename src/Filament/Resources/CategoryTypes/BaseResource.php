<?php

namespace Wsmallnews\Category\Filament\Resources\CategoryTypes;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Tables\CategoryTypesTable;
use Wsmallnews\Category\Filament\Widgets\Category as CategoryWidgets;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Filament\Resources\Concerns\Scopeable;
use UnitEnum;

abstract class BaseResource extends Resource
{
    use Scopeable;

    protected static ?string $model = CategoryType::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Bars3;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3;

    protected static ?string $navigationLabel = '分类类型';

    protected static string | UnitEnum | null $navigationGroup = '分类管理';

    protected static ?string $slug = 'category-types';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '分类类型';

    protected static ?string $pluralModelLabel = '分类类型';

    protected static ?int $navigationSort = 1;


    public static function form(Schema $schema): Schema
    {
        return CategoryTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryTypesTable::configure($table);
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
            ->scopeable(self::$scopeType, self::$scopeId)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
