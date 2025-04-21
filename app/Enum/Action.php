<?php

declare(strict_types=1);

namespace App\Enum;

enum Action: string
{
    case ARTICLE_LIST = 'article.list';
    case ARTICLE_DETAIL = 'article.detail';
    case ARTICLE_CREATE = 'article.create';
    case ARTICLE_UPDATE = 'article.update';
    case ARTICLE_DELETE = 'article.delete';

    case USER_LIST = 'user.list';
    case USER_DETAIL = 'user.detail';
    case USER_CREATE = 'user.create';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';
}
