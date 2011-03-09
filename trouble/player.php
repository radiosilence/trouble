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
	protected $_cycle;
	protected $_players;
	protected $_index;
	
	public function find_by_game(\Trouble\Game $game) {
		$results = $this->_storage->fetch(array(
            "joins" => array(
                new \Core\Join("agent", "Agent", array('id', 'alias'))
            ),
            "filter" => new \Core\Filter("game", $game['id']),
            "order" => new \Core\Order("id")
        ));
        $this->_players = \Core\Li::create();

        $this->_index = \Core\Dict::create();
        $i = 0;
        foreach($results as $result) {
            $this->_players->append($this->create_object($result));
        	$this->_index[$result['id']] = $i++;
        }
 		$this->_cycle = \Core\Li::create();
 		if(count($this->_players) > 0) {
            $this->_get_cycle($this->_players[0]);
     		$this->_populate_cycle();
        }
       	return $this->_cycle;
	}
	protected function _populate_cycle() {
		foreach($this->_cycle as $k => $v) {
			$this->_cycle[$k] = $this->_players[$this->_index[$v]];
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

	public function create_object($data) {
        $agent = Agent::mapper()->create_object(array(
            'id' => $data['agent'],
            'alias' => $data['agent_alias']
        ));

        return Player::create(array(
	        'id' => $data['id'],
	        'agent' => $agent,
	        'target' => $data['target'],
	        'status' => $data['credits']
	    ));
	}
}