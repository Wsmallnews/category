<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BackedEnum;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use UnitEnum;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

abstract class Base extends Page
{
    use Scopeable;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?CategoryType $categoryType = null;

    protected static ?string $pluralModelLabel = null;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $slug = 'categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $emptyLabel = null;

    protected static ?string $emptyTipLabel = null;

    /**
     * 是否可管理分类类型
     */
    protected static bool $canManage = false;

    protected string $view = 'sn-category::filament.pages.category.category-page';

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.filament.category.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('sn-category::category.filament.category.plural_model_label');
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('sn-category::category.filament.category.title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('sn-category::category.filament.category.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return static::$navigationGroup ?? __('sn-category::category.filament.category.navigation_group');
    }

    public static function getCanManage(): bool
    {
        return static::$canManage;
    }

    public static function getEmptyLabel(): ?string
    {
        return static::$emptyLabel ?? __('sn-category::category.filament.category.no_data');
    }

    public static function getEmptyTipLabel(): ?string
    {
        return static::$emptyTipLabel ?? __('sn-category::category.filament.category.no_data_description');
    }

    public static function getProperties(): array
    {
        return [
            'emptyLabel' => static::getEmptyLabel(),
            'emptyTipLabel' => static::getEmptyTipLabel(),
        ];
    }

    public function mount(): void
    {
        $this->categoryType = $this->getCategoryType();

        // 可管理分类类型，填充表单数据
        if (static::getCanManage()) {
            $attributes = $this->categoryType ? $this->categoryType->attributesToArray() : [];
            $attributes['level'] = $attributes['level'] ?? static::getLevel();      // 分类类型等级

            $this->form->fill($attributes);
        }
        parent::mount();
    }

    public function getCategoryType(): ?CategoryType
    {
        $categoryType = Utils::getCategoryTypeModel()::query()
            ->snScope(static::getScopeType(), static::getScopeId())
            ->first();

        if (! $categoryType && ! static::getCanManage()) {
            // 自动创建分类类型
            $categoryType = Utils::getCategoryTypeModel()::create([
                'name' => Str::title(static::getScopeType()),
                'level' => static::getLevel(),
                'status' => CategoryTypeStatus::Normal,
                ...static::getScopeable(),
                'team_id' => Filament::getTenant()?->id,
            ]);
        }

        return $categoryType;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Form::make(function () {
                    $forms = CategoryTypeForm::forms();

                    return $forms;
                })
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Schemas\Components\Actions::make([
                            Actions\Action::make('save')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->categoryType)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (! $this->categoryType) {
            $this->categoryType = new (Utils::getCategoryTypeModel());
            if (static::isScopedToTenant() && ($tenant = Filament::getTenant())) {
                $this->categoryType->team_id = $tenant->id;
            }
            $this->categoryType->scope_type = static::getScopeType();
            $this->categoryType->scope_id = static::getScopeId();
        }

        $this->categoryType->fill($data);
        $this->categoryType->save();

        if ($this->categoryType->wasRecentlyCreated) {
            $this->form->record($this->categoryType)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title(__('sn-category::category.category_management.save_success'))
            ->send();
    }
}
