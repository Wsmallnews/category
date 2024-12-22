<?php

namespace Wsmallnews\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Wsmallnews\Support\Models\SupportModel;

class Category extends SupportModel
{
    use HasFactory;
    use ModelTree;

    protected $table = 'sn_categories';

    protected $guarded = [];

    protected $casts = [
        'status' => \Wsmallnews\Category\Enums\CategoryStatus::class,
    ];

    public function scopeNormal($query)
    {
        return $query->where('status', 'normal');
    }

    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
