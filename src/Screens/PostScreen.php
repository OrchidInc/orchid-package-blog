<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Screens;

use Illuminate\Http\Client\Request;
use Illuminate\Http\RedirectResponse;
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
use OrchidInc\Orchid\Blog\Models\Post;

class PostScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'posts' => Post::query()
                ->filters()
                ->defaultSort('created_at', 'desc')
                ->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return __('Posts');
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus')
                ->canSee(auth()->user()->hasAccess(BlogEnum::postCreate))
                ->route(BlogEnum::postCreate),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('posts', [
                TD::make('title', __('Name'))
                    ->class('text-truncate')
                    ->width(400)
                    ->cantHide()
                    ->sort()
                    ->render(fn($post) => Link::make($post->title)->route(BlogEnum::postUpdate, $post->id)),

                TD::make('category', __('Category'))
                    ->alignCenter()
                    ->render(fn($post) => $post->category->name),

                TD::make('status', __('Status'))
                    ->alignCenter()
                    ->usingComponent(Boolean::class)
                    ->sort(),

                TD::make('image_id', __('Image'))
                    ->render(function ($post) {
                        $image = $post->attachment()->first();

                        return $image
                            ? '<img src="' . $image->url . '" height="50px" />'
                            : '&mdash;';
                    })
                    ->alignCenter()
                    ->defaultHidden(),

                TD::make('published_at', __('Published at'))
                    ->usingComponent(DateTimeSplit::class)
                    ->sort()
                    ->alignCenter(),

                TD::make('created_at', __('Created'))
                    ->usingComponent(DateTimeSplit::class)
                    ->alignCenter()
                    ->defaultHidden()
                    ->sort(),

                TD::make('updated_at', __('Last edit'))
                    ->usingComponent(DateTimeSplit::class)
                    ->alignCenter()
                    ->defaultHidden()
                    ->sort(),

                TD::make(__('Actions'))
                    ->alignCenter()
                    ->width('100px')
                    ->canSee(auth()->user()->hasAnyAccess([BlogEnum::postUpdate, BlogEnum::postDelete]))
                    ->render(fn(Post $post) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->icon('bs.pencil')
                                ->canSee(auth()->user()->hasAccess(BlogEnum::postUpdate))
                                ->route(BlogEnum::postUpdate, $post->id),

                            Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->canSee(auth()->user()->hasAccess(BlogEnum::postDelete))
                                ->confirm(__(BlogEnum::prefixPlugin . '::plugin_blog.confirm_delete'))
                                ->method('remove', ['id' => $post->id]),
                        ]))
                    ->cantHide(),
            ]),
        ];
    }

    public function permission(): ?iterable
    {
        return [BlogEnum::postView];
    }

    public function remove(Request $request): RedirectResponse
    {
        $post = Post::query()->findOrFail($request->get('id'));
        $post->delete();

        Toast::info(__('Removed'));

        return redirect()->route(BlogEnum::postView);
    }
}
