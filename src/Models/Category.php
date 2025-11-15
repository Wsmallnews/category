<?php

namespace Wsmallnews\Category\Models;

use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use Kalnoy\Nestedset\NodeTrait;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Models\SupportModel;

use function Filament\Support\generate_icon_html;

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
        $scopes = ['scope_type', 'scope_id', 'type_id'];
        if (Utils::isTenancyEnabled()) {        // 多租户 时，自动增加 租户相关参数
            $scopes[] = 'team_id';
        }

        return $scopes;
    }

    /**
     * 当前是否是激活状态
     *
     * @return Attribute
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return false;       // @sn todo 后续补充
            }
        );
    }

    /**
     * 当前model以及子model中是否存在 激活状态
     *
     * @return Attribute
     */
    protected function hasActive(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($this->is_active) {
                    return true;
                }

                // 收集当前 model 所有已经加载的 children (只收集两层 children数据))
                $allChildren = collect([]);
                if ($this->relationLoaded('children')) {
                    $allChildren = $this->children->flatMap(function ($child) {
                        if ($child->relationLoaded('children')) {
                            // 将当前元素和它的 children 数组合并成一个新数组
                            return collect([$child])->merge($child->children);
                        } else {
                            return collect([$child]);
                        }
                    });
                }

                return $allChildren->contains('is_active', true);
            }
        );
    }

    /**
     * model名称（包含 icon）
     *
     * @return Attribute
     */
    protected function nameLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                // 当前的model是否活动，或者子model中是否存在活动状态
                $hasActive = $this->has_active;
                $recordLabel = '<span class="flex items-center gap-2">';
                $icon_type = $this->options['icon_type'] ?? 'none';
                if ($icon_type == 'icon') {
                    if ($hasActive) {
                        $icon = $this->options['active_icon'] ?? ($this->options['icon'] ?? '');        // 优先取 活动图标
                    } else {
                        $icon = $this->options['icon'] ?? ($this->options['active_icon'] ?? '');        // 优先取非活动图标
                    }
                    $icon && $recordLabel .= generate_icon_html($icon, size: IconSize::Large)->toHtml();
                } elseif ($icon_type == 'image') {
                    if ($hasActive) {
                        $image = $this->options['active_icon_src'] ?? ($this->options['icon_src'] ?? '');    // 优先取 活动图标
                    } else {
                        $image = $this->options['icon_src'] ?? ($this->options['active_icon_src'] ?? '');   // 优先取非活动图标
                    }
                    $image && $recordLabel .= '<img src="' . files_url($image) . '" class="size-6" />';
                }

                $recordLabel .= $attributes['name'] . '</span>';

                return new HtmlString($recordLabel);
            },
        );
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
        return $this->belongsTo(Utils::getTenantModel());
    }
}
