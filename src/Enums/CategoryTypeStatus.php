<?php

namespace Wsmallnews\Category\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Wsmallnews\Support\Enums\Traits\EnumHelper;

enum CategoryTypeStatus: string implements HasColor, HasLabel
{
    use EnumHelper;

    case Normal = 'normal';

    case Disabled = 'disabled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Normal => '正常',
            self::Disabled => '禁用',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Normal => 'success',
            self::Disabled => 'danger',
        };
    }
}
