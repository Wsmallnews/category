<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Pages;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Resources\CategoryResource;

class IndexCategory extends Page
{
    protected static string $resource = CategoryResource::class;

    // protected static string $view = 'sn-category::resources.sn-category-resource.pages.index-sn-category';

    public function mount() {}

    public function records() {}

    public function form(Form $form): Form
    {
        // \DB::listen(function ($query) {
        //     echo $query->sql;
        //     // $query->bindings;
        //     // $query->time;
        // });

        return $form
            ->schema([
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
                // ->label('上级')
                // ->relationship('category', 'name', 'parent_id', function ($query) {
                //     return $query;
                // })
                //     ->searchable()
                //     ->parentNullValue(-1)
                //     ->emptyLabel(__('Oops, no results have been found!')),

                Components\TextInput::make('name')->label('名称')
                    ->required(),

                Components\FileUpload::make('image')->label('图片'),
                Components\TextInput::make('description')->label('描述')
                    ->required(),

                Components\Radio::make('status')->label('状态')
                    ->options(Enums\CategoryStatus::class)
                    ->default(Enums\CategoryStatus::Normal->value)
                    ->inline()
                    ->required(),
            ]);
    }
}
