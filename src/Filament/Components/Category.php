<?php

namespace Wsmallnews\Category\Filament\Components;

use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Guava\IconPicker\Forms\Components\IconPicker;
use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Category\Concerns\Resource\HasCustomProperties;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Models\Category as CategoryModel;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

class Category extends NestedsetPage
{
    // use HasCustomProperties;

    // 所属类型
    public ?Model $categoryType;

    protected static ?string $model = CategoryModel::class;


    public function mount(): void
    {
        $this->level = $this->categoryType?->level ?: 1;

        parent::mount();
    }


    public function createSchema($arguments): array
    {
        $arguments['type_id'] = $this->categoryType?->id;

        return $this->schema($arguments);
    }

    public function editSchema($arguments): array
    {
        return $this->schema($arguments);
    }

    public function infolistSchema(): array
    {
        return [
            Infolists\Components\TextEntry::make('description')
                ->label('描述')
                ->visible(fn ($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }


    protected function nestedScoped ()
    {
        return [
            'scope_type' => $this->categoryType?->scope_type,
            'scope_id' => $this->categoryType?->scope_id,
            'type_id' => $this->categoryType?->id,
        ];
    }


    protected function schema(array $arguments): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('description')->label('描述'),
            Forms\Components\ToggleButtons::make('options.icon_type')
                ->label('分类图标')
                ->options([
                    'none' => '无图标',
                    'icon' => 'icon图标',
                    'image' => '图片图标',
                ])
                ->default('none')
                ->inline(),
            Schemas\Components\FieldSet::make('icons')
                ->label('icon 图标')
                ->schema([
                    IconPicker::make('options.icon')
                        ->label('图标')
                        ->placeholder('请选择图标'),
                    IconPicker::make('options.active_icon')
                        ->label('活动图标')
                        ->placeholder('请选择活动图标'),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'icon'
                JS),
            Schemas\Components\FieldSet::make('image_icons')
                ->label('图片图标')
                ->schema([
                    Forms\Components\FileUpload::make('options.icon_src')
                        ->label('图标')
                        ->image()
                        // ->directory(Product::getImageDirectory())
                        ->openable()
                        ->downloadable()
                        ->uploadingMessage('图标上传中...')
                        ->imagePreviewHeight('100'),
                    Forms\Components\FileUpload::make('options.active_icon_src')
                        ->label('活动图标')
                        ->image()
                        // ->directory(Product::getImageDirectory())
                        ->openable()
                        ->downloadable()
                        ->uploadingMessage('活动图标上传中...')
                        ->imagePreviewHeight('100'),
                ])
                ->visibleJs(<<<'JS'
                    $get('options.icon_type') == 'image'
                JS),

            Forms\Components\Radio::make('status')
                ->label('状态')
                ->default(CategoryStatus::Normal)
                ->inline()
                ->options(CategoryStatus::class)
                ->columnSpan(1),
        ];
    }

    public function getEmptyLabel(): ?string
    {
        return '组件的属性这里要解决';
        return static::getCustomProperty('emptyLabel') ?? parent::getEmptyLabel();
    }
}
