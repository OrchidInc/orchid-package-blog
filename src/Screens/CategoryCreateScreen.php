<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Screens;

use Orchid\Screen\Screen;
use OrchidInc\Orchid\Blog\Enums\BlogEnum;
use OrchidInc\Orchid\Blog\Classes\CategoryCUTrait;

class CategoryCreateScreen extends Screen
{
    use CategoryCUTrait;

    public function permission(): ?iterable
    {
        return [BlogEnum::categoryCreate];
    }
}
