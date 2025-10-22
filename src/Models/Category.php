<?php

namespace Wsmallnews\Category\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Support\Models\SupportModel;

class Category extends SupportModel
{
    use NodeTrait;

    protected $table = 'sn_categories';

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'status' => CategoryStatus::class,
    ];

    public function getScopeAttributes(): array
    {
        return [];          // 'team_id'
    }

    public function scopeNormal($query)
    {
        return $query->where('status', 'normal');
    }

    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
