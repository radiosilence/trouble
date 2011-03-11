<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble;

import('core.mapping');
import('core.containment');

import('trouble.kill');
import('trouble.agent');
import('trouble.player');

class Game extends \Core\Mapped {
    public $kills;
    public $players;

    public function __construct($array=False) {
        parent::__construct($array);
    }

    public function load_kills($args=False) {
        $this->kills = $this->_mappers['Kill']
                ->find_by_game($this, $args);
        return $this;
    }

    public function get_players() {
        $p = Player::mapper()
                ->attach_storage(\Core\Storage::container()
                    ->get_storage('Player'))
            ->find_by_game($this);
        $this->players = $p[0];
        $this->all_players = $p[1];
        return $this;
    }

    protected function _check_players_loaded() {
        if(!$this->players) {
            $this->get_players();
        }
    }
    
    public function get_current_target($agent_id) {
        $this->_check_players_loaded();
        $target_id = $this->players->contains($agent_id, 'agent','id')
                ->target;
        return $this->players->contains($target_id, 'id')
                ->agent;
    }
    public function is_joined($agent_id) {
        $this->_check_players_loaded();
        return $this->players->contains($agent_id, 'agent', 'id');
        
    }

    public function _was_ever_in_game($agent_id) {
        $this->_check_players_loaded();
        return $this->all_players->contains($agent_id, 'agent', 'id');
    }

    public function add_agent($agent_id) {
        $this->_check_players_loaded();

        if(!$this->joinable) {
            throw new GameNotJoinableError();
        }

        $this->_storage = \Core\Storage::container()
            ->get_storage('Player');

        if($this->is_joined($agent_id)) {
            throw new GameAlreadyHasAgentError();
        }
        
        if($this->_was_ever_in_game($agent_id)) {
            throw new GameCannotRejoinError();
        }

        $player = $this->_create_player($agent_id);
        try {
            $this->_insert_player_into_cycle($player);
        } catch(GameError $e) {
            $this->_storage->delete($player);        
        }
        return $this;
    }

    protected function _create_player($agent_id) {
        $player = Player::create(array(
            'agent' => $agent_id,
            'game' => $this->id,
            'status' => 1,
            'credits' => 0,
            'target' => -1
        ));
        
        $this->_storage->save($player);
        return $player;
    }

    protected function _insert_player_into_cycle($player) {
        $s = $this->_storage;

        if(count($this->players) < 1) {
            $player->target = $player->id;
        } else {
            $place = mt_rand(0, count($this->players)-1);
            $old = $this->players[$place];
            $player->target = $old->target;
            $old->target = $player->id;
            $s->save($old);
        }
        $s->save($player);
    }

    public function kill_agent($agent_id) {
        $this->_check_players_loaded();
        $this->_storage = \Core\Storage::container()
            ->get_storage('Player');

        $player = $this->_get_player($agent_id);
        $player->status = 0;
        $hunter = $this->_remove_player_from_cycle($player);
        //$hunter_agent = Agent::container()
        //    ->get_by_id($hunter->agent);
        // increase $hunter_agent kill count by 1
    }

    public function resurrect_agent($hunter) {
    }

    protected function _get_player($agent_id) {
        $players = Player::mapper()
            ->attach_storage($this->_storage)
            ->get_list(array(
                'filters' => array(
                    new \Core\Filter('game', $this->id),
                    new \Core\Filter('agent', $agent_id)
                )
            ));
        if(count($players) == 0) {
            throw new GameAgentNotInGameError();
        }
        return $players[0];
    }
    public function remove_agent($agent_id) {
        $this->_check_players_loaded();
    
        $this->_storage = \Core\Storage::container()
            ->get_storage('Player');
        $player = $this->_get_player($agent_id);
        $player->status = -1;
        $this->_remove_player_from_cycle($player);
        if($this->state <= 0) {
            $this->_delete_player($player);
        }
    }

    protected function _remove_player_from_cycle($player) {
        if($player->target < 1) {
            return False;
        }
        $hunter = $this->players->contains($player->id, 'target');
        $hunter->target = $player->target;
        $player->target = -1;
        $this->_storage->save($hunter);
        $this->_storage->save($player);
        return $hunter;
    }

    protected function _delete_player($player) {
        $this->_storage->delete($player);
    }
}
class GameError extends \Core\StandardError {}
class GameNotJoinableError extends GameError {}
class GameAlreadyHasAgentError extends GameError {}
class GameAgentNotInGameError extends GameError {}
class GameCannotRejoinError extends GameError {}

class GameMapper extends \Core\Mapper {
    private $_default_order = array("start_date", "desc");

    public function get_list($parameters=False) {
        if(!$order) {
            $order = $_default_order;
        }
        $parameters['joins'][] = new \Core\Join("victor", "Player", False,
            new \Core\Join("agent", "Agent", array('alias')
        ));
        $results = $this->_storage->fetch($parameters);
        $games = new \Core\Li();
        foreach($results as $result) {
            $games->append($this->create_object($result));            
        }
        return $games;
    }

    public function create_object($data) {
        $data['victor'] = Agent::mapper()->create_object(array(
            'id' => $data['victor'],
            'alias' => $data['victor_agent_alias']
        ));
        unset($data['victor_alias']);
        $game = Game::create($data);
        $game->attach_mapper('Kill',
            Kill::mapper()
                ->attach_storage($this->_storage)
        );
        $game->attach_mapper('Agent',
            Agent::mapper()
                ->attach_storage(
                \Core\Storage::container()
                    ->get_storage('Agent')
                )
            );
        foreach(array('start_date', 'end_date', 'signup_date') as $f) {
            $game[$f] = new \DateTime($game[$f]);
        }
        $game['joinable'] = $game['state'] < 1;
        $game['active'] = $game['state'] == 1; 
        //$game->get_players();
        return $game;
    }
}

class GameContainer extends \Core\MappedContainer {
}
?>
