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
    '^get/([a-zA-Z_]+)$' => 'Get:method=$1',
    '^weapon/([0-9]+)' => 'Weapon:weapon_id=$1',
    '^create-game' => 'Game:method=edit',
    '^weapon/([0-9]+)' => 'Weapon:weapon_id=$1',
    '^game/([0-9]+)/intel/([^/]+)$' => 'Game:game_id=$1;method=intel;agent_alias=$2',
    '^game/([0-9]+)/?$' => 'Game:game_id=$1',
    '^games/starting-soon(.*)$' => 'Game:method=starting_soon',
    '^games(.*)$' => 'Game:method=ending_soon',
    '^your-games' => 'Game:method=your_games',
    '^sign-up' => 'Agent:method=edit',
    '^edit-profile' => 'Agent:method=edit',
    '^agent/([^/]+)$' => 'Agent:alias=$1',
    '^news/([0-9]+)(.*)' => 'News:method=display_article;article_id=$1',
    '^news' => 'News',
    '^highscores' => 'Highscores',
    '^forums' => 'Forums',
    '^login' => 'Put:method=login',
    '^logout' => 'Put:method=logout',
    '^$' => 'News'
);
