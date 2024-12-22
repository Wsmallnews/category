<?php

namespace Wsmallnews\Category\Resources\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Throwable;
use Wsmallnews\Category\Category as CategoryManager;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Concerns\RepeaterTree;

class Category extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use RepeaterTree;

    protected static ?string $navigationGroup = '分类管理';

    protected static ?string $navigationLabel = '分类管理';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'sn-category::resources.pages.category';

    #[Locked]
    public Model | int | string | null $record = null;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    // public function mount(int | string | null $record): void
    public function mount(): void
    {
        $this->record = CategoryType::where('scope_type', 'shop')->first();

        $this->fillForm();
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make('分类类型设置')
                    ->schema([
                        Components\TextInput::make('name')
                            ->label('类型名称')
                            ->placeholder('请输入分类类型名称')
                            ->required(),
                        Components\TextInput::make('description')
                            ->label('类型描述')
                            ->placeholder('请输入分类类型描述')
                            ->required(),
                        Components\Radio::make('level')
                            ->label('分类级别')
                            ->options([
                                1 => '一级分类',
                                2 => '二级分类',
                                3 => '三级分类',
                            ])
                            ->default(1)
                            ->live()
                            ->inline()
                            ->inlineLabel(false)
                            ->required(),
                        Components\Radio::make('status')
                            ->label('状态')
                            ->options(Enums\CategoryTypeStatus::class)
                            ->default(Enums\CategoryTypeStatus::Normal->value)
                            ->inline()
                            ->inlineLabel(false)
                            ->required(),
                    ])
                    ->columns(2),
                Components\Section::make('分类设置')
                    ->schema(fn(Get $get): array => [
                        $this->repeaterField($this->getFieldsTree($get('level')), relation_name: 'categories')->hiddenLabel(),
                    ])
                    // ->schema([
                    //     $this->repeaterField($this->getFieldsTree(3), relation_name: 'categories')->hiddenLabel(),
                    // ])
                    ->columns(1)
            ]);
    }


    public function fields(): array
    {
        return [
            Components\Group::make([
                Components\TextInput::make('name')
                    ->hiddenLabel()
                    ->placeholder('请输入分类名称')
                    ->required(),

                Components\Group::make([
                    Components\FileUpload::make('icon')
                        ->hiddenLabel()
                        ->placeholder('上传图标')
                        ->image()
                        ->directory(CategoryManager::getImageDirectory())
                        ->openable()
                        ->imageResizeMode('cover')
                        ->imageResizeUpscale(false)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetHeight('100')
                        ->imageResizeTargetWidth('100')
                        ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                        ->imagePreviewHeight('64')
                        ->removeUploadedFileButtonPosition('center bottom')
                        ->uploadProgressIndicatorPosition('right bottom')
                        // ->extraAttributes([
                        //     'class' => 'w-16 h-16 overflow-hidden border border-gray-300 rounded-md',
                        // ])
                        ->uploadingMessage('分类图标上传中...')
                        ->columnSpan(1),
                    Components\FileUpload::make('image')
                        ->hiddenLabel()
                        ->placeholder('上传图片')
                        ->image()
                        ->directory(CategoryManager::getImageDirectory())
                        ->openable()
                        ->imageResizeMode('cover')
                        ->imageResizeUpscale(false)
                        ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                        ->imagePreviewHeight('64')
                        ->removeUploadedFileButtonPosition('center bottom')
                        ->uploadProgressIndicatorPosition('right bottom')
                        ->uploadingMessage('分类图片上传中...')
                        ->columnSpan(1),
                ])
                ->columns(2),

                Components\TextInput::make('description')
                    ->hiddenLabel()
                    ->placeholder('请输入分类描述')
                    ->required(),

                Components\Radio::make('status')
                    ->hiddenLabel()
                    ->options(Enums\CategoryStatus::class)
                    ->default(Enums\CategoryStatus::Normal->value)
                    ->inline()
                    ->required()
            ])
            ->columns(4)
            ->columnSpanFull(),
        ];
    }


    public function getRecord(): ?CategoryType
    {
        return $this->record;
    }


    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($this->getRecord()?->attributesToArray() ?? []);

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
            // ->label(__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
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
                    ->model($this->getRecord() ?? new CategoryType())
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
