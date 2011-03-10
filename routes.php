<?php

/*
 * This file is part of trouble.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * In order of precedence
 */
$routes = array(
    '^put/([a-zA-Z_]+)$' => 'Put:method=$1',
    '^game/([0-9]{1,9})/?(.*)$' => 'Game:method=$2;game_id=$1',
    '^games/starting-soon(.*)$' => 'Game:method=starting_soon',
    '^games(.*)$' => 'Game:method=ending_soon',
    '^sign-up' => 'Agent:method=edit',
    '^edit-profile' => 'Agent:method=edit',
    '^agent/([^/]+)/edit$' => 'Agent:method=edit;alias=$1',
    '^agent/([^/]+)$' => 'Agent:method=index;alias=$1',
    '^agent/validate/([a-zA-Z_]+)$' => 'Agent:method=async_validate;field=$1',
    '^news/([0-9]+)(.*)' => 'News:method=display_article;article_id=$1',
    '^news' => 'News',
    '^highscores' => 'Highscores',
    '^forums' => 'Forums',
    '^login' => 'Put:method=login',
    '^logout' => 'Index:method=logout',
    '^$' => 'Index'
);
