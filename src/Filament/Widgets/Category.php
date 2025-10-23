<?php

namespace Wsmallnews\Category\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Livewire\Livewire;

class Category extends Widget
{
    protected string $view = 'sn-category::filament.widgets.category';

    protected int | string | array $columnSpan = 'full';

    public ?Model $record = null;
}