<?php

namespace Wsmallnews\Category\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Wsmallnews\Support\Enums\Traits\EnumHelper;

Enum CategoryStatus :string implements HasColor, HasLabel
{

    use EnumHelper;

    case Normal = 'normal';

    case Hidden = 'hidden';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Normal => '正常',
            self::Hidden => '隐藏',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Normal => 'success',
            self::Hidden => 'gray',
        };
    }

}