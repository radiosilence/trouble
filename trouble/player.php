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
    public static $fields = array('agent', 'target', 'game', 'status', 'credits', 'pkn');
}

class PlayerMapper extends \Core\Mapper {
	public function create_object($data) {
        $agent = Agent::mapper()->create_object(array(
            'id' => $data['agent'],
            'alias' => $data['agent_alias'],
            'avatar' => $data['agent_avatar']
        ));

        return Player::create(array(
	        'id' => $data['id'],
	        'agent' => $agent,
	        'target' => $data['target'],
            'credits' => $data['credits'],
            'pkn' => $data['pkn'],
	        'status' => $data['status']
	    ));
	}
}

class PlayerContainer extends \Core\MappedContainer {
	protected $_cycle;
	protected $_players;
	protected $_index;
	
	public function find_by_game(\Trouble\Game $game) {
		$this->_players = \Trouble\Player::container()
			->get(array(
	            "joins" => array(
	                new \Core\Join("agent", "Agent", array('id', 'alias', 'avatar'))
	            ),
	            "filters" => array(
		            new \Core\Filter("game", $game['id']),
		            new \Core\Filter("status", "-1", ">")
		        ),
	            "order" => new \Core\Order("id")
        	));
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
        }
       	return array($this->_cycle, $this->_players);
	}
	protected function _populate_cycle() {
		foreach($this->_cycle as $k => $v) {
			$this->_cycle[$k] = $this->_alive_players[$this->_index[$v]];
		}	
	}

	protected function _get_cycle(Player $player) {
		if($this->_cycle->filter($player['id'], 'id')) {
			return False;
		} else {
			$this->_cycle->append($player);
			$this->_get_cycle($this->_alive_players[$this->_index[$player['target']]]);
		}  
	}

	public function get_by_agent_game($game_id, $agent_id) {
        return \Trouble\Player::container()
			->get(array(
	            "joins" => array(
	                new \Core\Join("agent", "Agent", array('id', 'alias', 'avatar'))
	            ),
	            "filters" => array(
		            new \Core\Filter("game", $game_id),
		            new \Core\Filter("agent", $agent_id)
		        ),
	            "order" => new \Core\Order("id")
        	))->{0};

	}
}