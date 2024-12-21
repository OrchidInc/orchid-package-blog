<?php

declare(strict_types = 1);

namespace OrchidInc\Orchid\Blog\Screens;

use OrchidInc\Orchid\Blog\Classes\BlogEnum;
use OrchidInc\Orchid\Blog\Classes\CategoryCUTrait;
use Orchid\Screen\Screen;

class CategoryUpdateScreen extends Screen
{
    use CategoryCUTrait;

    public function permission(): ?iterable
    {
        return [BlogEnum::categoryUpdate];
    }
}
