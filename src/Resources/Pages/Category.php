<?php

namespace Wsmallnews\Category\Resources\Pages;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Wsmallnews\Category\Category as CategoryManager;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Models\Category as CategoryModel;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Features\Tree;
use Wsmallnews\Support\Resources\Pages\FormPage;
use Wsmallnews\Support\Traits\Components\CanScopeable;
use Wsmallnews\Support\Traits\Resources\SetResource;

class Category extends FormPage
{
    use CanScopeable;
    use SetResource;

    protected static ?string $navigationGroup = '分类管理';

    protected static ?string $navigationLabel = '分类管理';

    protected static ?string $navigationIcon = 'iconsax-bol-category';

    protected static ?string $title = '分类管理';

    protected static ?string $slug = 'category';

    public function mount()
    {
        $this->record = CategoryType::scopeable($this->scope_type, $this->scope_id)->firstOrFail();

        $this->record->categories = (new Tree(function () {
            return CategoryModel::query()->scopeable($this->scope_type, $this->scope_id)->where('type_id', $this->record->id);
        }))->getTree();

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
                            ->placeholder('请输入分类类型描述'),
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
                    ->schema(fn (Get $get): array => [
                        $this->repeaterField($this->getFieldsTree($get('level')), relation_name: 'categories')->hiddenLabel(),
                    ])
                    ->columns(1),
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
                    ->placeholder('请输入分类描述'),

                Components\Radio::make('status')
                    ->hiddenLabel()
                    ->options(Enums\CategoryStatus::class)
                    ->default(Enums\CategoryStatus::Normal->value)
                    ->inline()
                    ->required(),
            ])
                ->columns(4)
                ->columnSpanFull(),
        ];
    }

    // public static function getNavigationUrl(): string
    // {
    //     return static::getUrl(parameters: [
    //         // 'scope_type' => 'smallnews',
    //     ]);
    // }
}
