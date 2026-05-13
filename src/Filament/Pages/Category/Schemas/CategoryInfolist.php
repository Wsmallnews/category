<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Schemas;

use Filament\Infolists;

class CategoryInfolist
{
    public static function infolist(): array
    {
        return [
            Infolists\Components\TextEntry::make('description')
                ->label(__('sn-category::category.category_infolist.description'))
                ->visible(fn ($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label(__('sn-category::category.category_infolist.status')),
        ];
    }
}
