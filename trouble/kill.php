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

import('core.mapping.pdo');
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

class KillMapper extends \Core\Mapping\PDOMapper {
	protected $_select = '
	   SELECT
           kills.*,
	       assassin.alias as a_alias,
	       target.alias as t_alias,
	       weapons.name as w_name
	   FROM kills
	';
    protected $_joins = '
	   LEFT JOIN weapons ON kills.weapon = weapons.id
	   LEFT JOIN agents assassin ON kills.assassin = assassin.id
	   LEFT JOIN agents target ON kills.target = target.id
	';
    
    public function find_by_game(\Trouble\Game $game, $limit=20) {
        $kills = array();
        $sth = $this->pdo->prepare(
            $this->_select . $this->_joins .
            'WHERE game = :game
            LIMIT :limit
        ');
        $sth->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $sth->bindValue(':game', $game->id, \PDO::PARAM_INT);
        $sth->execute();
        while($data = $sth->fetchObject()) {
            $kills[]=$this->create_object($data);
        }
        return $kills;
    }
    
    public function create_object($data) {
        $assassin = Agent::mapper()->create_object(array(
            'id' => $data->assassin,
            'alias' => $data->a_alias
        ));
        $target = Agent::mapper()->create_object(array(
            'id' => $data->target,
            'alias' => $data->t_alias
        ));
        $weapon = Weapon::mapper()->create_object(array(
            'id' => $data->weapon,
            'name' => $data->w_name
        ));
        return Kill::create(array(
            'id' => $data->id,
            'description' => $data->description,
            'assassin' => $assassin,
            'target' => $target,
            'weapon' => $weapon,
            'when_happened' => $data->when_happened
        ));
    }
}

?>
