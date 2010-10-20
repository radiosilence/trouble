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

class Index extends \Trouble\GamePage {
    public function index() {
        \Trouble\WEAPON::attach_pdo($this->pdo);
    
        if(!$game->is_loaded) {
            die("Game was not loaded, so killboard not made.");
        }
        $killboard_c = new \Trouble\Killboard\Container(array(
            'pdo' => $this->pdo
        ));
        $killboard = $killboard_c->get_game_killboard($this->game);
    }

}
