<?php

/*
 * This file is part of trouble.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controllers;

import('trouble.weapon');
import('trouble.pages');
import('trouble.killboard');
import('core.types');
class Game extends \Trouble\GamePage {
    public function index() {
    }
    public function killboard() {
        $killboard = \Trouble\Killboard::container()->get_game_killboard($this->game);
        echo "<pre>Killboard data\n";
        var_dump($killboard);
        $this->view->show('home');
    }

}
