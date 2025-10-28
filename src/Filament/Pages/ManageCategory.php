<?php

namespace App\Filament\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;

class ManageCategory extends Page
{
    protected string $view = 'sn-category::filament.pages.category';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?CategoryType $record = null;

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
                ])
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }


    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord();

        if (! $record) {
            $record = new CategoryType();
            $record->scope_type = 'default_shop';
        }

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        $this->record = $record;

        Notification::make()
            ->success()
            ->title('保存成功')
            ->send();
    }


    public function getRecord(): ?CategoryType
    {
        return CategoryType::query()
            ->where('scope_type', 'default_shop')
            ->where('scope_id', 8)
            ->first();
    }
}
