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

import('core.storage');
import('core.types');
import('trouble.weapon');
import('trouble.pages');
import('trouble.killboard');
import('trouble.kill');
import('trouble.game');
import('trouble.agent');

class Game extends \Trouble\GamePage {
    public function index() {}
    public function killboard() {
        $t = new \Core\Template();
        $kill_storage = \Core\Storage::container()
            ->get_storage('Kill');
        $kill_mapper = \Trouble\Kill::mapper()
            ->attach_storage($kill_storage);
        if(!($this->game instanceof \Trouble\Game)) {
            throw new \Core\Error("No game.");
        }
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
        $time = new \DateTime("now");
        $t->games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(new \Core\Dict(array(
                "order" => new \Core\Order('end_date', 'asc'),
                "filters" => new \Core\Li(
                    new \Core\Filter("end_date", $time->format('c'), '<'),
                    new \Core\Filter("name", "Test Game")
                ))
            ));
        $t->content = $t->render('games_list.php');
        $t->title = "Games Ending Soon";
        echo $t->render('main.php');
    }

    public function starting_soon() {
        $t = new \Core\Template();
        $t->games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(new \Dict(array(
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
