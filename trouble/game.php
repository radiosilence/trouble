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
        $this->players = Player::mapper()
                ->attach_storage(\Core\Storage::container()
                ->get_storage('Player'))
            ->find_by_game($this);
            
        return $this;
    }
    public function add_agent($id) {
        if(!$this->joinable) {
            throw new GameNotJoinableError();
        }
        $this->get_players();
        if($this->players->contains($id)) {
            throw new GameAlreadyHasAgentError();
        }
        
        return $this;
    }
}

class GameNotJoinableError extends \Core\StandardError {}
class GameAlreadyHasAgentError extends \Core\StandardError {}

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
