<?php

declare(strict_types=1);

namespace OrchidInc\Orchid\Blog\Enums;

use Orchid\Platform\ItemPermission;

enum BlogEnum
{
    public const author = 'orchidinc';

    public const prefixPlugin = self::author . '.blog';

    public const prefix = self::prefixPlugin . '.';

    public const postfixView = 'view';

    public const postfixCreate = 'create';

    public const postfixUpdate = 'update';

    public const postfixDelete = 'delete';

    public const postView = self::prefix . 'posts.' . self::postfixView;

    public const postCreate = self::prefix . 'posts.' . self::postfixCreate;

    public const postUpdate = self::prefix . 'posts.' . self::postfixUpdate;

    public const postDelete = self::prefix . 'posts.' . self::postfixDelete;

    public const categoryView = self::prefix . 'categories.' . self::postfixView;

    public const categoryCreate = self::prefix . 'categories.' . self::postfixCreate;

    public const categoryUpdate = self::prefix . 'categories.' . self::postfixUpdate;

    public const categoryDelete = self::prefix . 'categories.' . self::postfixDelete;

    public static function permissions()
    {
        return ItemPermission::group(__('permission_header'))
            ->addPermission(self::postView, __('post_view'))
            ->addPermission(self::postCreate, __('post_create'))
            ->addPermission(self::postUpdate, __('post_edit'))
            ->addPermission(self::postDelete, __('post_delete'))
            ->addPermission(self::categoryView, __('category_view'))
            ->addPermission(self::categoryCreate, __('category_create'))
            ->addPermission(self::categoryUpdate, __('category_edit'))
            ->addPermission(self::categoryDelete, __('category_delete'));
    }
}
