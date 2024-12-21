<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Screens;

use Orchid\Screen\Screen;
use OrchidInc\Orchid\Blog\Classes\BlogEnum;
use OrchidInc\Orchid\Blog\Classes\PostCUTrait;

class PostCreateScreen extends Screen
{
    use PostCUTrait;

    public function permission(): ?iterable
    {
        return [BlogEnum::postCreate];
    }
}
