<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Classes;

use Orchid\Platform\ItemPermission;

enum BlogEnum
{
    const author = 'orchidinc';

    const prefixPlugin = self::author . '.blog';
    const prefix = self::prefixPlugin . '.';

    const postfixView = 'view';
    const postfixCreate = 'create';
    const postfixUpdate = 'update';
    const postfixDelete = 'delete';

    const postView = self::prefix . 'posts.' . self::postfixView;
    const postCreate = self::prefix . 'posts.' . self::postfixCreate;
    const postUpdate = self::prefix . 'posts.' . self::postfixUpdate;
    const postDelete = self::prefix . 'posts.' . self::postfixDelete;

    const categoryView = self::prefix . 'categories.' . self::postfixView;
    const categoryCreate = self::prefix . 'categories.' . self::postfixCreate;
    const categoryUpdate = self::prefix . 'categories.' . self::postfixUpdate;
    const categoryDelete = self::prefix . 'categories.' . self::postfixDelete;

    static public function permissions()
    {
        return ItemPermission::group(__(trans('permission_header')))
            ->addPermission(self::postView, __(trans('post_view')))
            ->addPermission(self::postCreate, __(trans('post_create')))
            ->addPermission(self::postUpdate, __(trans('post_edit')))
            ->addPermission(self::postDelete, __(trans('post_delete')))
            ->addPermission(self::categoryView, __(trans('category_view')))
            ->addPermission(self::categoryCreate, __(trans('category_create')))
            ->addPermission(self::categoryUpdate, __(trans('category_edit')))
            ->addPermission(self::categoryDelete, __(trans('category_delete')));
    }
}
