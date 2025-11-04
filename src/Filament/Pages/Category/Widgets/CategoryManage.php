<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;
use Wsmallnews\Category\Models\CategoryType;

class CategoryManage extends Widget
{
    #[Reactive]
    public ?CategoryType $record = null;

    public ?array $properties = [];
    
    protected int | string | array $columnSpan = 'full';
    
    protected string $view = 'sn-category::filament.pages.category.widgets.category-manage';
}
