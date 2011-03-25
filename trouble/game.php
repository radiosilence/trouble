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
    public $all_players;

    public static $fields = array("name", "start_date", "end_date", "location",
        "victor", "location", "description", "invite_only",
        "entry_fee", "creator", 'password');

    public function load_kills($args=False) {
        $this->kills = KillContainer::find_by_game($this, $args);
        return $this;
    }

    public function get_players($force=False) {
        if(!$this->players || $force) {
            $p = Player::container()
                ->find_by_game($this);
            $this->players = $p[0];
            $this->all_players = $p[1];            
        }
        return $this;
    }

    protected function _check_players_loaded() {
        if(!$this->players) {
            $this->get_players();
        }
    }
    
    public function get_current_target(Player $player) {
        $this->_check_players_loaded();
        $res = $this->players->filter($player->target, 'id');
        return $res[0]->agent;
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
        $res = $this->all_players->filter($agent_id, 'agent', 'id');
        if($res) {
            return True;
        } else {
            return False;
        }
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
        $target = $this->_get_player_from_cycle($player->target);
        $kill_data['target'] = $target['id'];
        $kill_data['assassin'] = $player['id'];
        $kill_data['game'] = $this->id;
        $kill = Kill::create($kill_data);
        $kill->check_valid_date($this->start_date, $this->end_date);

        $this->_kill_player($target, $kill_data['pkn']);

        \Core\Storage::container()
            ->get_storage('Kill')
            ->save($kill);
    }

    protected function _kill_player(Player $player, $pkn) {
        if($player->pkn != $pkn) {
            throw new GameIncorrectPKNError();
        }
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
        $res = $this->all_players->filter($player_id, 'id');
        return $res[0];
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
        $hunter = $this->players->filter($player->id, 'target');
        $hunter = $hunter[0];
        $hunter->target = $player->target;
        $player->target = -1;
        $this->_storage->save($hunter);
        $this->_storage->save($player);
        return $hunter;
    }

    protected function _delete_player(Player $player) {
        $this->_storage->delete($player);
    }

    public static function validation() {
       return array(
            'name' => array(
                'type' => 'default',
                'title' => 'Game Name'
            )
        );
    }
}
class GameError extends \Core\StandardError {}
class GameNotJoinableError extends GameError {}
class GameAlreadyHasAgentError extends GameError {}
class GameAgentNotInGameError extends GameError {}
class GameCannotRejoinError extends GameError {}
class GameNotStartedError extends GameError {}
class GameEndedError extends GameError {}
class GameIncorrectPKNError extends GameError {}

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
        if(empty($data['victor'])) {
            $data['victor'] = 0;
        } else {
            $data['victor'] = Agent::mapper()->create_object(array(
                'id' => $data['victor'],
                'alias' => $data['victor_agent_alias']
            ));
            unset($data['victor_alias']);
        }
        $game = Game::create($data, True);
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
        foreach(array('start_date', 'end_date') as $f) {
            $game[$f] = new \DateTime($game[$f]);
        }

        $game->invite_only = (int)$data['invite_only'];
        $now = new \DateTime();

        if($game->start_date > $now) {
            $game['joinable'] = True;
            $game['active'] = False;
            $game['state'] = 0;
        }
        else if($game->start_date < $now && $game->end_date > $now) {
            $game['joinable'] = False;
            $game['active'] = True;
            $game['state'] = 1;
        } else {
            $game['joinable'] = False;
            $game['active'] = False;
            $game['state'] = 2;
        }
        return $game;
    }
}

class GameContainer extends \Core\MappedContainer {
    public function get_by_id($id, $agent=False) {
        $params = static::params($agent);
        $params['filters'][] = new \Core\Filter('id', $id);
        $games = \Core\Storage::container()
            ->get_storage('Game')
            ->fetch($params);
        if(count($games) == 0) {
            return False;
        }
        return Game::mapper()->create_object($games[0]);
    }


    public static function params($agent=False) {
        if($agent) {
            $params = array(
                'binds' => array(
                        ':currentid' => $agent
                ),
                'fields' => array(
                        'games.id in(SELECT game FROM players WHERE agent = :currentid AND players.status >= 0) as joined'
                )
            );
            
        } else {
            $params = array();
        }
        $params['fields'][] = '(SELECT COUNT(id) FROM players WHERE players.game = games.id) as num_players';
        
        return $params;
    }

}
?>
