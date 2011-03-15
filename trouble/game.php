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
        $this->kills = KillContainer::find_by_game($this, $args);
        return $this;
    }

    public function get_players() {
        $p = Player::container()
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
    
    public function get_current_target(Player $player) {
        $this->_check_players_loaded();
        return $this->players->contains($player->target, 'id')
                ->agent;
    }

    public function get_killed_by(Player $player) {
        return Kill::container()
            ->get_by_victim($player, $this);
    }

    public function is_alive($player) {
        $this->_check_players_loaded();
        return $player->status;
        
    }

    public function is_joined($agent_id) {
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

        if($this->is_alive($agent_id)) {
            throw new GameAlreadyHasAgentError();
        }
        
        if($this->is_joined($agent_id)) {
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
            'target' => -1,
            'pkn' => str_pad(mt_rand(0,9999), 4, "0")
        ));
        
        $this->_storage->save($player);
        return $player;
    }

    protected function _insert_player_into_cycle(Player $player) {
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

    /**
     * Kill the target of $agent_id.
     * @param $agent_id The Agent ID of the <b>hunter</b>.
     */
    public function kill_agent_target($agent_id, array $kill_data) {
        $this->_check_players_loaded();
        if($this->state == 0) {
            throw new GameNotStartedError();
        }
        if($this->state == 2) {
            throw new GameEndedError();
        }
        $this->_storage = \Core\Storage::container()
            ->get_storage('Player');

        $player = $this->get_player($agent_id);
        $kill_data['target'] = $target['id'];
        $kill_data['assassin'] = $player['id'];
        $kill_data['game'] = $this->id;
        $kill = Kill::create($kill_data);
        $kill->check_valid_date($this->start_date, $this->end_date);

        $target = $this->_get_player_from_cycle($player->target);
        $this->_kill_player($target);

        \Core\Storage::container()
            ->get_storage('Kill')
            ->save($kill);
    }

    protected function _kill_player(Player $player) {
        $player->status = 0;
        $hunter = $this->_remove_player_from_cycle($player);
        //$hunter_agent = Agent::container()
        //    ->get_by_id($hunter->agent);
        // increase $hunter_agent kill count by 1
    }

    public function resurrect_agent(Player $hunter) {
    }

    /**
     * Get a \Trouble\Player by the agent ID.
     */
    public function get_player($agent_id) {
        $items = \Core\Storage::container()
            ->get_storage('Player')
            ->fetch(array(
                'filters' => array(
                    new \Core\Filter('game', $this->id),
                    new \Core\Filter('agent', $agent_id)
                )
            ));
        if(count($items) < 1) {
            throw new GameAgentNotInGameError();
        }
        $player = Player::mapper()
            ->create_object($items[0]);
        return $player;
    }

    /**
     * Get a \Trouble\Player from the cycle, by player ID. Alive or dead.
     */
    protected function _get_player_from_cycle($player_id) {
        return $this->all_players->contains($player_id, 'id');
    }
    public function remove_agent($agent_id) {
        $this->_check_players_loaded();
        $this->_storage = \Core\Storage::container()
            ->get_storage('Player');
        $player = $this->get_player($agent_id);
        if($player->status > 0) {
            $this->_remove_player_from_cycle($player);
        } 
        if($this->state <= 0) {
            $this->_delete_player($player);
        } else {
            $player->status = -1;
        }
        $this->_storage->save($player);
    }
    /**
     * Removes a player from the cycle and saves affected players in the storage.
     */
    protected function _remove_player_from_cycle(Player $player) {
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

    protected function _delete_player(Player $player) {
        $this->_storage->delete($player);
    }
}
class GameError extends \Core\StandardError {}
class GameNotJoinableError extends GameError {}
class GameAlreadyHasAgentError extends GameError {}
class GameAgentNotInGameError extends GameError {}
class GameCannotRejoinError extends GameError {}
class GameNotStartedError extends GameError {}
class GameEndedError extends GameError {}

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
                ->attach_storage(\Core\Storage::container()
                    ->get_storage('Kill')
                )
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
