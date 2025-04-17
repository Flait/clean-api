<?php

namespace App\Enum;

enum Action: string
{
    case CREATE_ARTICLE = 'create_article';
    case UPDATE_OWN_ARTICLE = 'update_own_article';
    case DELETE_ARTICLE = 'delete_article';
    case VIEW_ARTICLE = 'view_article';
}
