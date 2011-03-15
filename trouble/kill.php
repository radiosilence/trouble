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
import('core.types');

class Kill extends \Core\Mapped {
    protected $_fields = array('weapon', 'description', 'assassin',
        'target', 'when_happened', 'game');
	/**
	 * Registers kill of $assassin's target.
	 * Sets target as dead, makes a killboard stub.
	 */
	public function register_kill($target) {
		# 1. Initialise target
		# 2. Set target dead
		# 3. Initialise assassin
		# 4. Increase kill count
		# 5. Assign new target
		# 6. E-mail killer new target?
	}

    public function undo_kill($target) {
        # 1. Find last kill of target
        # 2. Reverse dat.
    }
}

class KillMapper extends \Core\Mapper {
    public function create_object($data) {
        $data = \Core\Dict::create($data);
        $assassin = Agent::mapper()->create_object(array(
            'id' => $data->assassin,
            'alias' => $data->assassin_agent_alias
        ));
        $target = Agent::mapper()->create_object(array(
            'id' => $data->target,
            'alias' => $data->target_agent_alias
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

class KillContainer extends \Core\MappedContainer {
    public static function get_by_victim(Player $victim, Game $game) {
        $items = \Core\Storage::container()
                ->get_storage('Kill')
                ->fetch(array(
                    "joins" => KillContainer::_standard_joins(),
                    'filters' => array(
                        new \Core\Filter('game', $game['id']),
                        new \Core\Filter('target', $victim['id'])
                    )
                ));
        return Kill::mapper()
            ->create_object($items[0]);        
    }

    public function find_by_game(\Trouble\Game $game, $limit=20) {
        $items = \Core\Storage::container()
                ->get_storage('Kill')
                ->fetch(array(
                    "joins" => KillContainer::_standard_joins(),
                    "filter" => new \Core\Filter("game", $game['id'])
                ));
        $kills = Kill::mapper()
            ->get_list($items);
        return $kills;
    }

    protected static function _standard_joins() {
        return array(
            new \Core\Join("weapon", "Weapon", array('id', 'name')),
            new \Core\Join("assassin", "Player", False,
                new \Core\Join("agent", "Agent", array('alias'))),
            new \Core\Join("target", "Player", False,
                new \Core\Join("agent", "Agent", array('alias')))
        );
    }
}
?>
