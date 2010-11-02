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
import('trouble.kill');
import('core.types');

class Game extends \Trouble\GamePage {
    public function index() {}
    public function killboard() {
        $t = new \Core\Template();
        $kill_mapper = \Trouble\Kill::mapper()
            ->attach_pdo($this->pdo);
        
        $this->game->attach_mapper('kill', $kill_mapper);

        $t->killboard = \Trouble\Killboard::container()
                ->get_game_killboard($this->game)
                ->load_data();

        $t->content = $t->render('killboard.php');
        $t->title = "Killboard";
        echo $t->render('main.php');
    }
}
