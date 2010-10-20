<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controllers;

import('trouble.weapon');
import('trouble.pages');
import('trouble.killboard.container');
import('core.types');

class Index extends \Trouble\GamePage {
    public function index() {
        if(!$this->game->is_loaded()) {
            die("Game was not loaded, so killboard not made.");
        }
        $killboard_c = new \Trouble\Killboard\Container();
        $killboard = $killboard_c->get_game_killboard($this->game);
        $killboard->get_data();

    }

}
