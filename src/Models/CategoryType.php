<?php

namespace Wsmallnews\Category\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Support\Models\SupportModel;

class CategoryType extends SupportModel
{
    use SoftDeletes;

    protected $table = 'sn_category_types';

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'status' => CategoryTypeStatus::class,
    ];

    public function scopeNormal($query)
    {
        return $query->where('status', 'normal');
    }

    public function scopeDisabled($query)
    {
        return $query->where('status', 'disabled');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'type_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
