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
import('controllers.standard_page');
import('trouble.weapon');
import('trouble.killboard');
import('trouble.kill');
import('trouble.game');
import('trouble.agent');
class Game extends \Controllers\GamePage {
    public function index() {}

    public function killboard() {
        $t = $this->_template;
        $g = $this->_game;
        $kill_storage = \Core\Storage::container()
            ->get_storage('Kill');
        $kill_mapper = \Trouble\Kill::mapper()
            ->attach_storage($kill_storage);
        if(!($this->_game instanceof \Trouble\Game)) {
            throw new \Core\Error("No game.");
        }
        $g->attach_mapper('Kill', $kill_mapper);
        $t->game = $g;
        $t->killboard = \Trouble\Killboard::container()
                ->get_game_killboard($g)
                ->load_data();

        $t->content = $t->render('killboard.php');
        $t->title = $t->game->name . ': Killboard';
        echo $t->render('main.php');
    }

    public function ending_soon() {
        $t = $this->_template;
        $time = new \DateTime("now");
        $params = array(
                "order" => new \Core\Order('end_date', 'asc')
//                "filter" => new \Core\Filter("end_date", $time->format('c'), '>')
        );
        try {
            $params["binds"] = array(
                    ':currentid' => $this->_auth->user_id()
            );
            $params["fields"] = array(
                    'games.id in(Select game from players where agent = :currentid) as joined'
            );
        } catch(\Core\AuthNotLoggedInError $e) {}
        $t->games = \Trouble\Game::mapper()
            ->attach_storage(\Core\Storage::container()
                ->get_storage('Game'))
            ->get_list($params);
        $t->content = $t->render('games_list.php');
        $t->title = "Games Ending Soon";
        echo $t->render('main.php');
    }

    public function players() {
        $game = \Trouble\Game::container()
            ->get_by_id($this->_args['game_id'])
            ->get_players();
    }

    public function starting_soon() {
        $t = $this->_template;
        $t->games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(array(
                "order" => new \Core\Order('end_date', 'asc'),
                "filters" => array(
                    new \Core\Filter("end_date", $time->format('c'), '>'),
                    new \Core\Filter("state", 1, "<")
                )
            ));

        $t->games->map(function($game) {
            $game->load_kills();
        });
        
        $t->content = $t->render('games_list.php');
        $t->title = "Games Starting Soon";
        echo $t->render('main.php');
    }
}
