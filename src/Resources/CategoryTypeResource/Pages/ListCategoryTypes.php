<?php

namespace Wsmallnews\Category\Resources\CategoryTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Resources\CategoryTypeResource;

class ListCategoryTypes extends ListRecords
{
    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        $labels = CategoryTypeStatus::labels();

        $tabs = [
            'all' => Tab::make('all')->label('全部')->icon('heroicon-m-user-group')        // 图标
                ->iconPosition(IconPosition::After)	// 图标位置
                ->badge(6)
        ];
        foreach ($labels as $key => $label) {
            $tabs[$label['value']] = Tab::make($label['value'])->label($label['name'])->modifyQueryUsing(fn (Builder $query) => $query->{$label['value']}());
        }

        return $tabs;
    }
}
