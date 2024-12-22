<?php

namespace Wsmallnews\Category\Resources;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SolutionForest\FilamentTree\Forms\Components\Tree;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Models\Category;
use Wsmallnews\Category\Resources\CategoryResource\Pages;
use Wsmallnews\Support\Traits\Resources\SetResource;

class CategoryResource extends Resource
{
    use SetResource;

    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = '分类管理';
    protected static ?string $navigationLabel = '分类管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '分类';
    protected static ?string $pluralModelLabel = '分类';

    protected static ?string $slug = '/categories';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // \DB::listen(function ($query) {
        //     // echo $query->sql;

        //     \Log::error($query->sql . json_encode($query->bindings));
        //     // $query->bindings;
        //     // $query->time;
        // });



        return $form
            ->schema([
                // Components\Repeater::make('members')
                //     ->schema([
                //         Components\TextInput::make('name')->required(),
                //         Components\Select::make('role')
                //             ->options([
                //                 'member' => 'Member',
                //                 'administrator' => 'Administrator',
                //                 'owner' => 'Owner',
                //             ])
                //             ->required(),
                //     ])
                //     // ->mutateRelationshipDataBeforeCreateUsing(function ($data): array {
                //     //     // $data['user_id'] = auth()->id();
                //     //     print_r($data);
                //     //     return $data;
                //     // })
                //     ->columns(2),

                // Components\TextInput::make('name')->afterStateUpdated(function (?string $state, ?string $old) {

                // })
                //             ->required(),

                // Tree::make('parent_id')
                //     ->relationship('category', function ($query) {
                //         return $query;
                //     }),
                TableRepeater::make('social')
                    ->columnWidths([
                        'name' => '100px',
                        'image' => '150px',
                        'description' => '100px',
                        'status' => '200px',
                    ])
                    ->hideLabels()
                    ->schema([
                        Components\TextInput::make('name')
                            ->required(),

                        Components\FileUpload::make('image'),
                        Components\TextInput::make('description')
                            ->required(),

                        Components\Radio::make('status')
                            ->options(Enums\CategoryStatus::class)
                            ->default(Enums\CategoryStatus::Normal->value)
                            ->inline()
                            ->required(),
                    ])
                    ->columnSpan('full'),



                // SelectTree::make('parent_id')
                //     ->label('上级')
                //     ->relationship('category', 'name', 'parent_id', function ($query) {
                //         return $query;
                //     })
                //     ->searchable()
                //     ->parentNullValue(-1)
                //     ->emptyLabel(__('Oops, no results have been found!')),



                // Components\TextInput::make('name')->label('名称')
                //     ->required(),

                // Components\FileUpload::make('image')->label('图片'),
                // Components\TextInput::make('description')->label('描述')
                //     ->required(),

                // Components\Radio::make('status')->label('状态')
                //     ->options(Enums\CategoryStatus::class)
                //     ->default(Enums\CategoryStatus::Normal->value)
                //     ->inline()
                //     ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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


    /**
     * @return array<class-string<Widget>>
     */
    // public static function getWidgets(): array
    // {
    //     return [
    //         // CategoryWidget::class
    //     ];
    // }


    public static function getPages(): array
    {
        return [
            // 'index' => Pages\IndexCategory::route('/'),
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
