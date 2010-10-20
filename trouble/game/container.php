<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble\Game;

import('trouble.game.instance');
import('core.container');

class Container extends \Core\Container {
    public function get_game($game_id) {
        $game = new \Trouble\Game\Instance();
        $this->test_valid_parameter('pdo','\PDO');
        $game->attach_pdo($this->parameters['pdo']);
        $game->load($game_id);
        return $game;
    }
}

?>