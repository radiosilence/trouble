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

import('trouble.game');
import('trouble.agent');
import('trouble.weapon');

class Kill extends \Core\Mapped {

	/**
	 * Registers kill of $assassin's target.
	 * Sets target as dead, makes a killboard stub.
	 */
	public function register_kill() {
		# 1. Initialise target
		# 2. Set target dead
		# 3. Initialise assassin
		# 4. Increase kill count
		# 5. Assign new target
		# 6. E-mail killer new target?
	}
}

class KillMapper extends \Core\Mapper {

    public function find_by_game(\Trouble\Game $game, $limit=20) {
        $results = $this->_storage->fetch(array(
            "joins" => array(
                new \Core\Join("weapon", "Weapon", new \Core\Li('id', 'name')),
                new \Core\Join("assassin", "Agent", new \Core\Li('alias')),
                new \Core\Join("target", "Agent", new \Core\Li('alias'))
            ),
            "filter" => new \Core\Filter("game", $game['id'])
        ));
        $kills = new \Core\Li();
        $kills = \Core\Li::create();
        foreach($results as $result) {
            $kills->append($this->create_object($result));
        }  
        return $kills;
    }
    
    public function create_object($data) {
        $data = \Core\Dict::create($data);
        $assassin = Agent::mapper()->create_object(array(
            'id' => $data->assassin_id,
            'alias' => $data->assassin_alias
        ));
        $target = Agent::mapper()->create_object(array(
            'id' => $data->target_id,
            'alias' => $data->target_alias
        ));
        $weapon = Weapon::mapper()->create_object(array(
            'id' => $data->weapon_id,
            'name' => $data->weapon_name
        ));
        return Kill::create(array(
            'id' => $data->id,
            'description' => $data->description,
            'assassin' => $assassin,
            'target' => $target,
            'weapon' => $weapon,
            'when_happened' => new \DateTime($data->when_happened)
        ));
    }
}

?>
