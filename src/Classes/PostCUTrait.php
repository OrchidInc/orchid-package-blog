<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Classes;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use MrVaco\Status\Classes\StatusHelper;
use MrVaco\Status\Models\Status;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use OrchidInc\Orchid\Blog\Models\Category;
use OrchidInc\Orchid\Blog\Models\Post;

trait PostCUTrait
{
    public $post;

    public function query(Post $post): iterable
    {
        return [
            'post' => $post,
        ];
    }

    public function name(): ?string
    {
        return $this->post->exists
            ? __('Update :name', ['name' => $this->post->title])
            : __('Create post');
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Cancel'))
                ->icon('bs.x')
                ->route(BlogEnum::postView),

            Button::make(__('Remove'))
                ->type(Color::DANGER)
                ->icon('bs.trash3')
                ->canSee($this->post->exists && auth()->user()->hasAccess(BlogEnum::postDelete))
                ->confirm(__('Confirm delete'))
                ->method('remove'),

            Button::make(__('Save'))
                ->type(Color::SUCCESS)
                ->icon('bs.check-circle')
                ->canSee(auth()->user()->hasAccess(BlogEnum::postCreate))
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::split([
                Layout::rows([
                    Input::make('post.title')
                        ->title(__('Name'))
                        ->type('text')
                        ->max(255)
                        ->required()
                        ->horizontal(),

                    Input::make('post.slug')
                        ->title(__('Slug'))
                        ->type('text')
                        ->max(255)
                        ->horizontal(),

                    Input::make('post.keywords')
                        ->title(__('Keywords'))
                        ->type('text')
                        ->max(255)
                        ->horizontal(),

                    Input::make('post.tags')
                        ->title(__('Tags'))
                        ->type('text')
                        ->max(255)
                        ->horizontal(),

                    Quill::make('post.introductory')
                        ->title(__('Introductory'))
                        ->type('text')
                        ->max(255)
                        ->height('200px')
                        ->required()
                        ->horizontal(),

                    Quill::make('post.content')
                        ->title(__('Content'))
                        ->type('text')
                        ->max(255)
                        ->height('600px')
                        ->required(),
                ]),

                Layout::rows([
                    Relation::make('post.category_id')
                        ->title(__('Category'))
                        ->fromModel(Category::class, 'name')
                        ->applyScope('active')
                        ->value(1),

                    Relation::make('post.status')
                        ->title(__('Status'))
                        ->fromModel(Status::class, 'name')
                        ->value(StatusHelper::ACTIVE('base')->id),

                    DateTimer::make('post.published_at')
                        ->title(__('Published at'))
                        ->value(Carbon::now())
                        ->format24hr()
                        ->enableTime()
                        ->serverFormat('Y-m-d H:i:s'),

                    CheckBox::make('post.recommended')
                        ->title('Recommended')
                        ->value(false)
                        ->sendTrueOrFalse()
                        ->placeholder(__('Yes')),

                    Cropper::make('post.image_id')
                        ->title(__('Image'))
                        ->minCanvas(300)
                        ->maxCanvas(450)
                        ->width(450)
                        ->height(300)
                        ->targetId(),
                ]),
            ])->ratio('60/40'),
        ];
    }

    public function save(Post $post, Request $request): RedirectResponse
    {
        $request->validate([
            'post.title' => [
                'required',
            ],
            'post.introductory' => [
                'required',
            ],
            'post.content' => [
                'required',
            ],
        ]);

        $post->fill($request->collect('post')->toArray())->save();
        $post->attachment()->syncWithoutDetaching(
            $request->input('post.image_id', [])
        );

        Toast::success(__('Saved'));

        return redirect()->route(BlogEnum::postView);
    }

    public function remove(Post $post): RedirectResponse
    {
        $post->delete();

        Toast::info(__('Deleted'));

        return redirect()->route(BlogEnum::postView);
    }
}
