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
    '^game/([0-9]{1,9})/?(.*)$' => 'Game:method=$2;game_id=$1',
    '^games/starting-soon(.*)$' => 'Game:method=starting_soon',
    '^games(.*)$' => 'Game:method=ending_soon',
    '^agent/create' => 'Agent:method=edit',
    '^agent/([^/]+)/edit$' => 'Agent:method=edit;alias=$1',
    '^agent/([^/]+)$' => 'Agent:method=agent;alias=$1',
    '^agent/validate/([a-zA-Z_]+)$' => 'Agent:method=async_validate;field=$1',
    '^action/([a-zA-Z_]+)$' => 'Action:method=$1',
    '^news(.*)$' => 'News',
    '^$' => 'Index:method=index'
);
/*
RewriteRule ^/?game/([0-9]{1,9})/(.*)$ index.php?route=game/$2/game_id:$1
RewriteRule ^/?games(.*)$ index.php?route=game/ending_soon/$1
RewriteRule ^/?games/starting-soon(.*)$ index.php?route=game/starting_soon/$1
RewriteRule ^/?agent/([\w]+).*?(\.html)?$ index.php?route=agent/alias:$1
*/
