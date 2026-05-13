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
use UnitEnum;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Filament\Pages\Concerns\Scopeable;

abstract class ManageBase extends Page
{
    use Scopeable;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?CategoryType $record = null;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Bars3BottomLeft;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bars3BottomLeft;

    protected static ?string $slug = 'manage-categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected string $view = 'sn-category::filament.pages.category.manage-category';

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('sn-category::category.category_management.manage_title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('sn-category::category.category_management.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return static::$navigationGroup ?? __('sn-category::category.category_management.navigation_group');
    }

    public function mount(): void
    {
        $this->record = $this->getRecord();
        $this->form->fill($this->record?->attributesToArray());
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
            ->record($this->record)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (! $this->record) {
            $this->record = new (Utils::getCategoryTypeModel());
            if (static::isScopedToTenant() && ($tenant = Filament::getTenant())) {
                $this->record->team_id = $tenant->id;
            }
            $this->record->scope_type = static::getScopeType();
            $this->record->scope_id = static::getScopeId();
        }

        $this->record->fill($data);
        $this->record->save();

        if ($this->record->wasRecentlyCreated) {
            $this->form->record($this->record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title(__('sn-category::category.category_management.save_success'))
            ->send();
    }

    public function getRecord(): ?CategoryType
    {
        return Utils::getCategoryTypeModel()::query()
            ->scopeable(static::getScopeType(), static::getScopeId())
            ->first();
    }

    public function getProperties(): array
    {
        return [
            'emptyLabel' => method_exists($this, 'getEmptyLabel') ? $this->getEmptyLabel() : null,
        ];
    }
}
