<?php

namespace Wsmallnews\Category\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Support\Contracts\HasSnSubject;
use Wsmallnews\Support\Models\Concerns\HasActivityLog;
use Wsmallnews\Support\Models\SupportModel;
use Wsmallnews\Support\Support\Utils as SupportUtils;

class CategoryType extends SupportModel implements HasSnSubject
{
    use HasActivityLog;
    use SoftDeletes;

    /**
     * 搜索字段（用于 morphFilter 关键词搜索）。
     */
    public static array $keywordSearchFields = ['name', 'description'];

    protected $table = 'sn_category_types';

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'status' => CategoryTypeStatus::class,
    ];

    public function getSnSubjectId(): int
    {
        return $this->id;
    }

    public function getSnSubjectTitle(): string | HtmlString | null
    {
        return $this->name;
    }

    public function getSnSubjectDescription(): string | HtmlString | null
    {
        return $this->description;
    }

    public function getSnSubjectCoverUrl(): string | HtmlString | null
    {
        return null;
    }

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
        return $this->belongsTo(SupportUtils::getTenantModel());
    }
}
