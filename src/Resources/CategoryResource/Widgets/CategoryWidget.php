<?php

namespace Wsmallnews\Category\Resources\CategoryResource\Widgets;

use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Tables\Columns;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Actions\Action;
use SolutionForest\FilamentTree\Actions\ActionGroup;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Actions\ViewAction;
use SolutionForest\FilamentTree\Widgets\Tree as BaseWidget;
use Wsmallnews\Category\Models\Category;

class CategoryWidget extends BaseWidget
{
    use InteractsWithPageTable;

    protected static string $model = Category::class;

    protected static int $maxDepth = 2;

    protected ?string $treeTitle = 'SnCategoryWidget';

    protected bool $enableTreeTitle = true;

    public ?Model $record = null;

    protected function getFormSchema(): array
    {
        return [
            Components\TextInput::make('name'),
            Components\TextInput::make('description'),
        ];
    }

    // INFOLIST, CAN DELETE
    public function getViewFormSchema(): array
    {
        return [
            Columns\TextColumn::make('description'),
        ];
    }

    protected function getTablePage(): string
    {
        return ListSnCategories::class;
    }

    // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE
    // public function getTreeRecordIcon(?\Illuminate\Database\Eloquent\Model $record = null): ?string
    // {
    //     return null;
    // }

    // CUSTOMIZE ACTION OF EACH RECORD, CAN DELETE
    // protected function getTreeActions(): array
    // {
    //     return [
    //         Action::make('helloWorld')
    //             ->action(function () {
    //                 Notification::make()->success()->title('Hello World')->send();
    //             }),
    //         // ViewAction::make(),
    //         // EditAction::make(),
    //         ActionGroup::make([
    //
    //             ViewAction::make(),
    //             EditAction::make(),
    //         ]),
    //         DeleteAction::make(),
    //     ];
    // }
    // OR OVERRIDE FOLLOWING METHODS
    // protected function hasDeleteAction(): bool
    // {
    //    return true;
    // }
    // protected function hasEditAction(): bool
    // {
    //    return true;
    // }
    // protected function hasViewAction(): bool
    // {
    //    return true;
    // }
}
