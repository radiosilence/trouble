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
import('trouble.intel');
import('trouble.voucher');

class UserNotJoinedError extends \Core\StandardError {}
class Game extends \Controllers\GamePage {
    public function index() {
        $t = $this->_template;
        $g = $this->_game;
        $t->game = $g;
        $tabs = array();
        try {
            try {
                $this->_auth->check_admin('game', $g->id);
                $tabs['administration'] = array(
                    'title' => 'Administration',
                    'content' => $this->_edit()
                );
                $t->is_admin = True;
            } catch(\Core\AuthDeniedError $e) {} 

            if(!$g->is_joined($this->_auth->user_id())){
                throw new UserNotJoinedError();
            }
            $t->joined = True;
            $tabs['dashboard'] = array(
                'title' => 'Dashboard',
                'content' => $this->_game_dashboard()
            );

        } catch(\Core\AuthNotLoggedInError $e) {
        } catch(UserNotJoinedError $e) {
            $t->joined = False;
        }

        $tabs['information'] = array(
            'title' => 'Information',
            'content' => $this->_game_info()
        );
        $tabs['killboard'] = array(
            'title' => 'Killboard',
            'content' => $this->_game_killboard()
        );
        $t->title = $g->name;
        $t->tabs = $tabs;
        $t->tab_order = array('dashboard', 'information', 'killboard', 'administration');
        $t->content = $t->render('tabs.php');
        echo $t->render('main.php');
    }

    protected function _game_dashboard() {
        $uid = $this->_auth->user_id();
        $t = $this->_template;
        $g = $this->_game;
        $p = \Trouble\Player::container()
            ->get_by_agent_game($g->id, $uid);
        $t->player = $p;
        if($p->status > 0) {
            $t->target = $g->get_current_target($p);
            if($t->target->id == $uid) {
                $t->self_target = True;
            }            
        } else {
            $t->kill = $g->get_killed_by($p);
        }
        $t->_dialog_kill = $t->render('forms/kill.php');
        return $t->render('game_dashboard.php');
    }

    protected function _game_info() {
        $t = $this->_template;
        $t->_join_dialogs = $t->render('forms/join.php');
        return $t->render('game_info.php');
    }
    
    public function intel() {
        $t = $this->_template;
        $t->game = $this->_game;
        $t->content = $this->_intel();
        echo $t->render('main.php');
    }

    protected function _intel() {
        $t = $this->_template;
        $a = \Trouble\Agent::container()
            ->get_by_alias($this->_args['agent_alias']);
        $t->agent = $a;
        $t->player = $this->_player;

        $targ = $this->_game->all_players
            ->filter($a->id, 'agent')->{0};
        if($targ->id == $t->player->target && $t->player->status == 1) {
            $t->is_target = True;
            $t->_dialog_kill = $t->render('forms/kill.php');
        }
        $t->owned_intels = \Trouble\OwnedIntel::container()
            ->get_owned_intel($a, $this->_player);

        return $t->render('intel.php');
    }
    
    public function _game_killboard() {
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
        $t->killboard = \Trouble\Killboard::container()
                ->get_game_killboard($g)
                ->load_data();

        return $t->render('killboard.php');
    }

    public function _game_rules() {
        
    }

    public function games_list() {
        $t = $this->_template;
        $t->tabs = array(
            'starting-soon' => array(
                'title' => 'Waiting for Players',
                'content' => $this->_starting_soon()
            ),
            'ending-soon' => array(
                'title' => 'In Progress',
                'content' => $this->_in_progress()
            )
        );
        $t->dialogs = $t->render('forms/join.php');
        $t->content = $t->render('tabs.php');
        $t->title = "Games";
        echo $t->render('main.php');
    }

    protected function _starting_soon() {
        $t = $this->_template;
        $now = new \DateTime();
        try {
            $params = \Trouble\GameContainer::params($this->_auth->user_id());
        } catch(\Core\AuthNotLoggedInError $e) {
            $params = \Trouble\GameContainer::params();
        }
        $params['filters'][] = new \Core\Filter('start_date', $now->format('c'), '>');
        $params['order'] = new \Core\Order('start_date', 'asc');
        
        $t->games = \Trouble\Game::container()
            ->get($params);
            
        return $t->render('games_list.php');
    }

    protected function _in_progress() {
        $t = $this->_template;
        $now = new \DateTime();

        try {
            $params = \Trouble\GameContainer::params($this->_auth->user_id());
        } catch(\Core\AuthNotLoggedInError $e) {
            $params = \Trouble\GameContainer::params();
        }
        $params['filters'][] = new \Core\Filter('end_date', $now->format('c'), '>');
        $params['filters'][] = new \Core\Filter('start_date', $now->format('c'), '<');
        $params['order'] = new \Core\Order('end_date', 'asc');
        
        $t->games = \Trouble\Game::container()
            ->get($params);
            
        return $t->render('games_list.php');

    }

    public function players() {
        $game = \Trouble\Game::container()
            ->get_by_id($this->_args['game_id'])
            ->load_players();
    }

    protected function _user_games($user) {
        $t = $this->_template;
        $params = \Trouble\GameContainer::params($user);
        $params['orders'][] = new \Core\Order('end_date', 'asc');
        $params["filters"][] = \Core\Filter::create_complex('games.id in(Select game from players where agent = :currentid)');
        $t->games = \Trouble\Game::container()
            ->get($params);
        $t->title = "Your Games";
        return $t->render('games_list.php');
    }

    protected function _administrated_games() {
        $t = $this->_template;
        $ids = $this->_auth->get_administrated_ids('game');
        $t->games = new \Core\Li();
        if(count($ids) > 0) {
            $params = \Trouble\GameContainer::params($this->_auth->user_id());
            $params['filters'][] = new \Core\Filter('id', $ids, 'in');
            $params['order'] = new \Core\Order('start_date', 'desc');
            $t->games->extend(\Trouble\Game::container()
                ->get($params));
        }

        return $t->render('games_list.php');
        
    }

    public function your_games() {
        $t = $this->_template;
        $tabs = array();
        try {
            $tabs['joined'] = array(
                'title' => 'Joined',
                'content' => $this->_user_games($this->_auth->user_id())
            );
            $tabs['administrated'] = array(
                'title' => 'Administrated',
                'content' => $this->_administrated_games()
            );
            $t->title = "Your Games";
            $t->tabs = $tabs;
            $t->content = $t->render('tabs.php');
            echo $t->render('main.php');
        } catch(\Core\AuthNotLoggedInError $e) {
            header("Location: /");
        }
    }

    public function edit() {
        $t = $this->_template;
        $t->content = $this->_edit();
        echo $t->render('main.php');   
    }

    protected function _edit() {
        $t = $this->_template;
        $t->add('_jsapps', 'game_form');

        $storage = \Core\Storage::container()
            ->get_storage('Game');
        $mapper = \Trouble\Game::mapper()
            ->attach_storage($storage);
        try {
            if($this->_args['game_id']) {
                $t->title = "Edit Game";
                $game = $this->_game;
                $this->_auth->check_admin('game', $game->id);
                $game->form_start_date = $game->start_date->format('Y-m-d');
                $game->form_end_date = $game->end_date->format('Y-m-d');
                $t->administration = $this->_administration($game);
                if($game->invite_only == 2) {
                    $t->vouchers = $this->_vouchers($game);                    
                }
            } else {
                $t->title = "Game Creation";
                $t->new = True;
                $game = \Trouble\Game::create($_POST, True);
            }

            $t->game = $game;

            return $t->render('forms/game.php');
        } catch(\Core\AuthDeniedError $e) {
            throw new \Core\HTTPError(401, "Editing Game");
        } catch(\Core\AuthNotLoggedInError $e) {
            throw new \Core\HTTPError(401, "Editing Game");
        }
    }
    protected function _vouchers($game) {
        $t = $this->_template;
        return $t->render('vouchers_admin.php');
    }
    protected function _administration($game) {
        $t = $this->_template;
        $game->load_players();
        $t->all_players = $game->all_players;
        return $t->render('game_admin.php');
    }

    public function generate_vouchers() {
        $t = $this->_template;
        if($_args['number'] > 280) {
            $_args['number'] = 280;
        } else if($_args['number'] < 1) {
            $_args['number'] = 0;
        }
        try {
            $this->_auth->check_admin('game', $this->_game->id);
            $vouchers = array();
            for($i=0; $i<$this->_args['number']; $i++) {
                array_push($vouchers, \Trouble\Voucher::container()
                    ->make_new($this->_args['type'], $this->_game));
            }
            $t->vouchers = $vouchers;
            $t->types = array(
                'join' => 'Game Join Code',
                'credit1' => '1 Credit',
                'credit2' => '2 Credits',
                'credit5' => '5 Credits',
                'credit10' => '10 Credits',
                'credit20' => '20 Credits'
            );
            echo $t->render('vouchers_print.php');
        } catch(\Core\AuthError $e) {
            throw new \Core\HTTPError(403, 'generate-vouchers');
        }

    }
}
