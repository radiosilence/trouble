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
import('trouble.game');
import('core.types');

class Game extends \Trouble\GamePage {
    public function index() {}
    public function killboard() {
        $t = new \Core\Template();
        $kill_mapper = \Trouble\Kill::mapper()
            ->attach_pdo($this->pdo);
        
        $this->game->attach_mapper('kill', $kill_mapper);
        $t->game = $this->game;
        $t->killboard = \Trouble\Killboard::container()
                ->get_game_killboard($this->game)
                ->load_data();

        
        $t->content = $t->render('killboard.php');
        $t->title = $t->game->name;
        echo $t->render('main.php');
    }

    public function ending_soon() {
        $t = new \Core\Template();

        $time = new \DateTime("now");
        $t->games = \Trouble\Game::mapper()
            ->attach_pdo($this->pdo)
            ->get_list('games.end_date asc', 20, sprintf("where games.end_date > '%s'", $time->format('c')));
        $t->games->map(function($game) {
            $game->load_kills();
        });
        
        $t->content = $t->render('games_list.php');
        $t->title = "Games Ending Soon";
        echo $t->render('main.php');
    }
}
