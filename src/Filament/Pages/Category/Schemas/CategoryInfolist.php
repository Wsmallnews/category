<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Schemas;

use Filament\Infolists;

class CategoryInfolist
{
    public static function infolist(): array
    {
        return [
            Infolists\Components\TextEntry::make('description')
                ->label('描述')
                ->visible(fn ($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }
}
