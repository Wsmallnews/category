<?php

namespace Wsmallnews\Category\Resources\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Throwable;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Forms\Fields\TableRepeater;

class Category extends Page
{
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static ?string $navigationGroup = '分类管理';
    protected static ?string $navigationLabel = '分类管理';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'sn-category::resources.pages.category';


    #[Locked]
    public Model | int | string | null $record;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    // public function mount(int | string | null $record): void
    public function mount(): void
    {
        $this->record = CategoryType::find(1);

        $this->fillForm();
    }

    public function getRecord(): CategoryType
    {
        return $this->record;
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TableRepeater::make('categories')
                    ->relationship('categories')
                    ->columnWidths([
                        'name' => '100px',
                        'image' => '150px',
                        'description' => '100px',
                        'status' => '200px',
                    ])
                    ->view('sn-support::forms.fields.table-repeater-old')
                    ->hideLabels()
                    ->reorderable(true)
                    ->schema([
                        Components\TextInput::make('name')->label('名称')
                            ->required(),

                        Components\FileUpload::make('image')->label('图片'),
                        Components\TextInput::make('description')->label('描述')
                            ->required(),

                        Components\Radio::make('status')->label('状态')
                            ->options(Enums\CategoryStatus::class)
                            ->default(Enums\CategoryStatus::Normal->value)
                            ->inline()
                            ->required(),

                        TableRepeater::make('categories')
                            ->relationship('children')
                            ->columnWidths([
                                'name' => '100px',
                                'image' => '150px',
                                'description' => '100px',
                                'status' => '200px',
                            ])
                            // ->isFusionLayout(true)
                            ->view('sn-support::forms.fields.table-repeater-old')
                            ->withoutHeader()
                            ->hideLabels()
                            ->schema([
                                Components\TextInput::make('name')->label('名称')
                                    ->required(),

                                Components\FileUpload::make('image')->label('图片'),
                                Components\TextInput::make('description')->label('描述')
                                    ->required(),

                                Components\Radio::make('status')->label('状态')
                                    ->options(Enums\CategoryStatus::class)
                                    ->default(Enums\CategoryStatus::Normal->value)
                                    ->inline()
                                    ->required(),
                                // TableRepeater::make('categories')
                                //     ->relationship('children')
                                //     ->columnWidths([
                                //         'name' => '100px',
                                //         'image' => '150px',
                                //         'description' => '100px',
                                //         'status' => '200px',
                                //     ])
                                //     // ->isFusionLayout(true)
                                //     ->withoutHeader()
                                //     ->hideLabels()
                                //     ->schema([
                                //         Components\TextInput::make('name')->label('名称')
                                //             ->required(),

                                //         Components\FileUpload::make('image')->label('图片'),
                                //         Components\TextInput::make('description')->label('描述')
                                //             ->required(),

                                //         Components\Radio::make('status')->label('状态')
                                //             ->options(Enums\CategoryStatus::class)
                                //             ->default(Enums\CategoryStatus::Normal->value)
                                //             ->inline()
                                //             ->required(),
                                //     ])
                            ])
                    ])
                    ->columnSpan('full'),
            ]);
    }


    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($this->getRecord()->attributesToArray());

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            // $settings = app(static::getSettings());

            // $settings->fill($data);
            // $settings->save();

            // 这里保存分类呀

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    public function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    public function getSavedNotificationTitle(): ?string
    {
        return $this->getSavedNotificationMessage() ?? __('filament-spatie-laravel-settings-plugin::pages/settings-page.notifications.saved.title');
    }

    /**
     * @deprecated Use `getSavedNotificationTitle()` instead.
     */
    protected function getSavedNotificationMessage(): ?string
    {
        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
        ->label(__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
        ->submit('save')
        ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }



    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema($this->getFormSchema())
                    ->model($this->getRecord())
                    ->statePath('data')
                    ->columns(2)
                    ->inlineLabel($this->hasInlineLabels()),
            ),
        ];
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

}
