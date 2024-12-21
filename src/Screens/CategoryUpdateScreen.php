<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Screens;

use Orchid\Screen\Screen;
use OrchidInc\Orchid\Blog\Classes\CategoryCUTrait;
use OrchidInc\Orchid\Blog\Enums\BlogEnum;

class CategoryUpdateScreen extends Screen
{
    use CategoryCUTrait;

    public function permission(): ?iterable
    {
        return [BlogEnum::categoryUpdate];
    }
}
