<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BackedEnum;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use UnitEnum;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\FilamentNestedset\Filament\Pages\NestedsetPage;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

abstract class Base extends NestedsetPage
{
    use Scopeable;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?CategoryType $categoryType = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $slug = 'categories';

    protected static ?int $navigationSort = 1;

    /**
     * 是否可管理分类类型
     */
    protected static bool $canManage = false;

    protected string $view = 'sn-category::filament.pages.category.category-page';

    public function mount(): void
    {
        $this->categoryType = static::getCategoryType();

        // 可管理分类类型，填充表单数据
        if (static::getCanManage()) {
            $attributes = $this->categoryType ? $this->categoryType->attributesToArray() : [];
            $attributes['level'] = $attributes['level'] ?? static::getLevel();      // 分类类型等级

            $this->form->fill($attributes);
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getNestedsetActions()
    {
        return [
            $this->createAction(),
            $this->fixNestedsetAction(),
        ];
    }

    public static function getModel(): ?string
    {
        return Utils::getCategoryModel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('sn-category::category.category_page.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('sn-category::category.category_page.plural_model_label');
    }

    public function getTitle(): string|Htmlable
    {
        return static::$title ?? __('sn-category::category.category_page.title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('sn-category::category.category_page.navigation_label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return static::$navigationGroup ?? __('sn-category::category.global_default.navigation_group');
    }

    public static function getCanManage(): bool
    {
        return static::$canManage;
    }

    public static function getLevel(): ?int
    {
        return static::$level;
    }

    public static function getEmptyLabel(): ?string
    {
        return static::$emptyLabel ?? __('sn-category::category.category_page.no_data');
    }

    public static function getEmptyTipLabel(): ?string
    {
        return static::$emptyTipLabel ?? __('sn-category::category.category_page.no_data_description');
    }

    public function getRecordLabel(Model $record): HtmlString|string
    {
        return $record->name_label;
    }

    public function nestedScoped(): array
    {
        return [
            'scope_type' => $this->categoryType?->scope_type,
            'scope_id' => $this->categoryType?->scope_id,
            'type_id' => $this->categoryType?->id,
        ];
    }

    public function createSchema(array $arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    public function editSchema(array $arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    public function schema(array $arguments): array
    {
        return CategoryForm::forms($arguments);
    }

    public function infolistSchema(): array
    {
        return CategoryInfolist::infolist();
    }

    public static function getCategoryType(): ?CategoryType
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
            ->title(__('sn-category::category.category_page.save_success'))
            ->send();
    }
}
