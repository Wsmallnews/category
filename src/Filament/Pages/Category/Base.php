<?php

namespace Wsmallnews\Category\Filament\Pages\Category;

use BackedEnum;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Locked;
use UnitEnum;
use Wsmallnews\Category\Enums\CategoryTypeStatus;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm;
use Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\FilamentNestedset\Filament\Pages\NestedsetPage;
use Wsmallnews\Support\Filament\Concerns\HasPanelScopedType;
use Wsmallnews\Support\Filament\Concerns\RequiresConfiguration;
use Wsmallnews\Support\Filament\Pages\PageConfiguration;

abstract class Base extends NestedsetPage
{
    use HasPanelScopedType;
    use RequiresConfiguration;

    protected static ?string $configurationClass = PageConfiguration::class;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    #[Locked]
    public ?CategoryType $categoryType = null;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $slug = 'categories';

    protected static ?int $navigationSort = 1;

    /**
     * 是否可管理分类类型
     */
    protected static bool $canManage = false;

    protected string $view = 'sn-category::filament.pages.category.category-page';

    public function mount(): void
    {
        $this->categoryType = $this->getCategoryType();

        static::$level = $this->categoryType?->level ?? null;

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

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('sn-category::category.category_page.title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('sn-category::category.category_page.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
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

    public function getCategoryType(): ?CategoryType
    {
        $categoryType = $this->resolvePanelType();

        // 不可管理模式，自动更新自定义设置的层级（仅在与库中值不同时写库）
        if ($categoryType && ! static::getCanManage() && (int) $categoryType->level !== (int) static::getLevel()) {
            $categoryType->level = static::getLevel();
            $categoryType->save();
        }

        return $categoryType;
    }

    protected function typeModel(): string
    {
        return Utils::getCategoryTypeModel();
    }

    protected function typeDefaults(): array
    {
        return [
            'name' => $this->typeNameFromScope(),
            'level' => static::getLevel(),
            'status' => CategoryTypeStatus::Normal,
        ];
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
            $this->categoryType->fill($this->panelScopedAttributes());
        }

        $this->categoryType->fill($data);
        $this->categoryType->save();

        if ($this->categoryType->wasRecentlyCreated) {
            $this->form->record($this->categoryType)->saveRelationships();
        }

        static::$level = $this->categoryType->level ?? null;

        Notification::make()
            ->success()
            ->title(__('sn-category::category.category_page.save_success'))
            ->send();
    }

    protected function getRecordLabel(Model $record): HtmlString | string
    {
        return $record->name_label;
    }

    protected function nestedScoped(): array
    {
        return [
            'scope_type' => $this->categoryType?->scope_type,
            'scope_id' => $this->categoryType?->scope_id,
            'type_id' => $this->categoryType?->id,
        ];
    }

    protected function createSchema(array $arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    protected function editSchema(array $arguments): array
    {
        $arguments = array_merge($arguments, $this->nestedScoped());

        return $this->schema($arguments);
    }

    protected function schema(array $arguments): array
    {
        return CategoryForm::forms($arguments);
    }

    protected function infolistSchema(): array
    {
        return CategoryInfolist::infolist();
    }
}
