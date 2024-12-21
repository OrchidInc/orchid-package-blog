<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Screens;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use OrchidInc\Orchid\Blog\Enums\BlogEnum;
use OrchidInc\Orchid\Blog\Models\Category;

class CategoryScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'categories' => Category::query()
                ->filters()
                ->defaultSort('id')
                ->get(),
        ];
    }

    public function name(): ?string
    {
        return __('Categories');
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus')
                ->canSee(auth()->user()->hasAccess(BlogEnum::categoryCreate))
                ->route(BlogEnum::categoryCreate),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('categories', [
                TD::make('name', __('Name'))
                    ->sort()
                    ->cantHide(),

                TD::make('slug', __('Slug'))
                    ->defaultHidden(),

                TD::make('tags', __('Tags'))
                    ->defaultHidden(),

                TD::make('posts', __('Posts'))
                    ->alignCenter()
                    ->render(fn($category) => count($category->posts)),

                TD::make('status', __('Status'))
                    ->alignCenter()
                    ->usingComponent(Boolean::class)
                    ->sort(),

                TD::make('created_at', __('Created'))
                    ->usingComponent(DateTimeSplit::class)
                    ->alignCenter()
                    ->defaultHidden()
                    ->sort(),

                TD::make('updated_at', __('Last edit'))
                    ->usingComponent(DateTimeSplit::class)
                    ->alignCenter()
                    ->sort(),

                TD::make(__('Actions'))
                    ->alignCenter()
                    ->width('100px')
                    ->canSee(auth()->user()->hasAnyAccess([BlogEnum::categoryUpdate, BlogEnum::categoryDelete]))
                    ->render(fn(Category $category) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->icon('bs.pencil')
                                ->canSee(auth()->user()->hasAccess(BlogEnum::categoryUpdate))
                                ->route(BlogEnum::categoryUpdate, $category->id),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->canSee(auth()->user()->hasAccess(BlogEnum::categoryDelete))
                                ->confirm(__(BlogEnum::prefixPlugin . '::plugin_blog.confirm_delete'))
                                ->method('remove', ['id' => $category->id]),
                        ]))
                    ->cantHide(),
            ]),
        ];
    }

    public function permission(): ?iterable
    {
        return [BlogEnum::categoryView];
    }

    public function remove(Request $request): RedirectResponse
    {
        $category = Category::query()->findOrFail($request->get('id'));
        $category->delete();

        Toast::info(__(BlogEnum::prefixPlugin . '::plugin_blog.removed'));

        return redirect()->route(BlogEnum::categoryView);
    }
}
