<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Classes;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use MrVaco\Status\Classes\StatusHelper;
use MrVaco\Status\Models\Status;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use OrchidInc\Orchid\Blog\Enums\BlogEnum;
use OrchidInc\Orchid\Blog\Models\Category;

trait CategoryCUTrait
{
    public $category;

    public function query(Category $category): iterable
    {
        return [
            'category' => $category,
        ];
    }

    public function name(): ?string
    {
        return $this->category->exists
            ? __('Update :name', ['name' => $this->category->name])
            : __('Create category');
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Cancel'))
                ->icon('bs.x')
                ->route(BlogEnum::categoryView),

            Button::make(__('Remove'))
                ->type(Color::DANGER)
                ->icon('bs.trash3')
                ->canSee($this->category->exists && auth()->user()->hasAccess(BlogEnum::categoryDelete))
                ->confirm(__('Confirm delete'))
                ->method('remove'),

            Button::make(__('Save'))
                ->type(Color::SUCCESS)
                ->icon('bs.check-circle')
                ->canSee(auth()->user()->hasAccess(BlogEnum::categoryCreate))
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('category.name')
                        ->title(__('Name'))
                        ->type('text')
                        ->max(255)
                        ->required(),

                    Input::make('category.slug')
                        ->title(__('Slug'))
                        ->type('text')
                        ->max(255)
                        ->required(),
                ]),

                Group::make([
                    Input::make('category.keywords')
                        ->title(__('Keywords'))
                        ->type('text')
                        ->max(255),

                    Input::make('category.tags')
                        ->title(__('Tags'))
                        ->type('text')
                        ->max(255),
                ]),

                Relation::make('category.status')
                    ->title(__('Status'))
                    ->fromModel(Status::class, 'name')
                    ->value(StatusHelper::ACTIVE('base')->id)
                    ->horizontal(),

                Quill::make('category.description')
                    ->title(__('Description'))
                    ->type('text')
                    ->max(255)
                    ->height('200px'),
            ]),
        ];
    }

    public function save(Category $category, Request $request): RedirectResponse
    {
        $request->validate([
            'category.name' => [
                'required',
            ],
            'category.slug' => [
                'required',
            ],
        ]);

        $category->fill($request->collect('category')->toArray())->save();

        Toast::success(__('Saved'));

        return redirect()->route(BlogEnum::categoryView);
    }

    public function remove(Category $category): RedirectResponse
    {
        $category->delete();

        Toast::info(__('Deleted'));

        return redirect()->route(BlogEnum::categoryView);
    }
}
