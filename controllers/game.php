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
import('trouble.agent');
import('core.types');

class Game extends \Trouble\GamePage {
    public function index() {}
    public function killboard() {
        $t = new \Core\Template();
        $kill_storage = \Trouble\Storage\PDO::create('Kill')
            ->attach_pdo($this->pdo);
        $kill_mapper = \Trouble\Kill::mapper()
            ->attach_storage($kill_storage);
        
        $this->game->attach_mapper('kill', $kill_mapper);
        $t->game = $this->game;
        $t->killboard = \Trouble\Killboard::container()
                ->get_game_killboard($this->game)
                ->load_data();

        
        $t->content = $t->render('killboard.php');
        $t->title = $t->game->name . ': Killboard';
        echo $t->render('main.php');
    }

    public function ending_soon() {
        $t = new \Core\Template();
echo "ENDING SOON";
        $time = new \DateTime("now");
        $t->games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(new \Core\CoreDict(array(
                "order" => new \Core\Order('end_date', 'asc'),
                "filters" => new \Core\CoreList(
                    new \Core\Filter("end_date", $time->format('c'), '>')
                ))
            ));
        $t->content = $t->render('games_list.php');
        $t->title = "Games Ending Soon";
        echo $t->render('main.php');
    }

    public function starting_soon() {
        $t = new \Core\Template();
echo "STARTING SOON";
        $t->games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(new \CoreDict(array(
                "order" => new \Core\Order('end_date', 'asc'),
                "filters" => array(
                    new \Core\Filter("end_date", $time->format('c'), '>'),
                    new \Core\Filter("state", 1, "<")
                ))
            ));

        $t->games->map(function($game) {
            $game->load_kills();
        });
        
        $t->content = $t->render('games_list.php');
        $t->title = "Games Starting Soon";
        echo $t->render('main.php');
    }
}
