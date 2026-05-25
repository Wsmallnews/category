<?php

namespace Wsmallnews\Category\Filament\Pages\Category\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\Reactive;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Livewire\Concerns\CanBeContained;
use Wsmallnews\Support\Livewire\Concerns\HasProperties;

class Category extends Widget
{
    use CanBeContained;
    use HasProperties;

    #[Reactive]
    public ?CategoryType $record = null;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'sn-category::filament.pages.category.widgets.category';
}
