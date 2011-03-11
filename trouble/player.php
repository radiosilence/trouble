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
import('core.storage');
import('core.types');
import('trouble.game');
import('trouble.agent');
import('trouble.weapon');

class Player extends \Core\Mapped {
    protected $_fields = array('agent', 'game', 'target', 'status', 'credits');
}

class PlayerMapper extends \Core\Mapper {
	public function create_object($data) {
        $agent = Agent::mapper()->create_object(array(
            'id' => $data['agent'],
            'alias' => $data['agent_alias']
        ));

        return Player::create(array(
	        'id' => $data['id'],
	        'agent' => $agent,
	        'target' => $data['target'],
	        'credits' => $data['credits'],
	        'status' => $data['status']
	    ));
	}
}

class PlayerContainer extends \Core\MappedContainer {
	protected $_cycle;
	protected $_players;
	protected $_index;
	
	public function find_by_game(\Trouble\Game $game) {
		$results = \Core\Storage::container()
			->get_storage('Player')
			->fetch(array(
	            "joins" => array(
	                new \Core\Join("agent", "Agent", array('id', 'alias'))
	            ),
	            "filters" => array(
		            new \Core\Filter("game", $game['id']),
		            new \Core\Filter("status", 0, ">=")
		        ),
	            "order" => new \Core\Order("id")
        	));
        $this->_players = Player::mapper()
        	->get_list($results);
        $this->_index = \Core\Dict::create();
        $this->_alive_players = \Core\Li::create();

        foreach($this->_players as $player) {
        	if($player->status > 0) {
        		$this->_alive_players->append($player);
        	}
        }
        
        for($i = 0; $i < count($this->_alive_players); $i++) {
        	$this->_index[$this->_alive_players[$i]['id']] = $i;
        }

 		$this->_cycle = \Core\Li::create();
 		if(count($this->_alive_players) > 0) {
            $this->_get_cycle($this->_alive_players[0]);
     		$this->_populate_cycle();
        }
       	return array($this->_cycle, $this->_players);
	}
	protected function _populate_cycle() {
		foreach($this->_cycle as $k => $v) {
			$this->_cycle[$k] = $this->_alive_players[$this->_index[$v]];
		}	
	}

	protected function _get_cycle($player) {
		if($this->_cycle->contains($player['id'])) {
			return False;
		} else {
			$this->_cycle->append($player['id']);

			$this->_get_cycle($this->_players[$this->_index[$player['target']]]);
		}  
	}

	public function get_by_agent_game($game_id, $agent_id) {
		$results = \Core\Storage::container()
			->get_storage('Player')
			->fetch(array(
	            "joins" => array(
	                new \Core\Join("agent", "Agent", array('id', 'alias'))
	            ),
	            "filters" => array(
		            new \Core\Filter("game", $game_id),
		            new \Core\Filter("agent", $agent_id)
		        ),
	            "order" => new \Core\Order("id")
        	));
        return Player::mapper()
        	->create_object($results[0]);
	}
}