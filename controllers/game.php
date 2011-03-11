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

class UserNotJoinedError extends \Core\StandardError {}
class Game extends \Controllers\GamePage {
    public function index() {
        $t = $this->_template;
        $g = $this->_game;
        $t->game = $g;
        try {
            if(!$g->is_joined($this->_auth->user_id())){
                throw new UserNotJoinedError();
            }
            $this->_show_game_dashboard();
        } catch(\Core\AuthNotLoggedInError $e) {
            $this->_show_game_info();
        } catch(UserNotJoinedError $e) {
            $this->_show_game_info();
        }

    }

    protected function _show_game_dashboard() {
        $t = $this->_template;
        $g = $this->_game;
        $t->title = $g->name . ': Dashboard';
        $t->target = $g->get_current_target($this->_auth->user_id());
        if($t->target->id == $this->_auth->user_id()) {
            $t->self_target = True;
        }
        $t->content = $t->render('game_dashboard.php');
        echo $t->render('main.php');
    }

    protected function _show_game_info() {
        $t = $this->_template;
        $g = $this->_game;
        $t->title = $g->name;
        $t->content = $t->render('game_info.php');
        echo $t->render('main.php');
    }

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

    protected function _standard_query_params($user) {
        $params = array(
            'binds' => array(
                    ':currentid' => $user
            ),
            'fields' => array(
                    '(SELECT COUNT(id) FROM players WHERE players.game = games.id) as num_players',
                    'games.id in(SELECT game FROM players WHERE agent = :currentid AND players.status >= 0) as joined'
            )
        );
        return $params;
    }
    public function ending_soon() {
        $t = $this->_template;
        $time = new \DateTime("now");
        try {
            $params = $this->_standard_query_params($this->_auth->user_id());
            $params['order'] = new \Core\Order('end_date', 'asc');
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

    public function user_games($user) {
        $t = $this->_template;
        try {
            $params = $this->_standard_query_params($user);
            $params['order'] = new \Core\Order('end_date', 'asc');
            $params["filter"] = \Core\Filter::create_complex('games.id in(Select game from players where agent = :currentid)');
            $t->games = \Trouble\Game::mapper()
                ->attach_storage(\Core\Storage::container()
                    ->get_storage('Game'))
                ->get_list($params);
            if($user = $this->_auth->user_id()) {
                $t->title = "Your Games";
            } else {
                $t->title = "Games for user.";
            }
        } catch(\Core\AuthNotLoggedInError $e) {
            $t->title = "Games for user.";
        }
    
        $t->content = $t->render('games_list.php');
        echo $t->render('main.php');
    }

    public function your_games() {
        try {
            $this->user_games($this->_auth->user_id());
        } catch(\Core\AuthNotLoggedInError $e) {
            header("Location: /");
        }
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

        $t->content = $t->render('games_list.php');
        $t->title = "Games Starting Soon";
        echo $t->render('main.php');
    }
}
