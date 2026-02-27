<?php

namespace Wsmallnews\Category\Livewire\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;
use Wsmallnews\FilamentNestedset\Livewire\Components\Nestedset;
use Wsmallnews\Support\Livewire\Concerns\Scopeable;

use function Filament\Support\generate_href_html;

class Categories extends Nestedset
{
    use Categoryable;
    use Scopeable;

    public bool $useUrl = false;

    public ?string $url = null;

    public string $queryName = 'categoryId';

    public bool $shouldOpenInNewTab = false;

    public function getRecordLabel(Model $record): HtmlString | string
    {
        return $record->name_label;
    }

    public function getRecordUrl(Model $record): string | HtmlString | null
    {
        // 启用 url 方式，并且没有子导航时，才返回 url
        if ($this->useUrl && ! $record->children->count()) {
            $url = $this->url ?? request()->fullUrlWithoutQuery($this->queryName);      // 默认使用当前 url, 移除 queryName 参数， 重新拼接新的 queryName 参数

            return generate_href_html($url . (Str::contains($url, '?') ? '&' : '?') . $this->queryName . '=' . $record->id, $this->shouldOpenInNewTab);
        }

        return null;
    }

    public function getHasActive(Model $record): bool
    {
        return $record->has_active;
    }

    public function getNestedset(): Collection
    {
        return $this->getScopedQuery()->normal()->defaultOrder()
            ->get()->toTree();
    }

    public function render(): View
    {
        return view($this->view);
    }
}
