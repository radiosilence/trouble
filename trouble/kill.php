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

class Kill {
	public function __construct( $db ) {
		$this->db = $db;
		$this->define();
	}

	protected function define() {
		$t = $this;
		
		$t->name		= "kill";
		$t->nice_name		= "Kill";
		
		$t->primary_table	= "kills";
		$t->primary_key 	= "kills.id";
		
		$t->table( "kills", array(
			"id" 		=> $t->primary_key	(),
			"weapon"	=> $t->foreign_key	( "Weapon", "weapon", "select" ),
			"description"	=> $t->text_field	( "Kill Description" ),
			"assassin"	=> $t->foreign_key	( "Assassin", "player", "search" ),
			"target"	=> $t->foreign_key	( "Target", "agent", "search" ),
			"contested"	=> $t->boolean_field	( "Contested" ),
			"contest"	=> $t->text_field	( "Contest Description" ),
			"timestamp"	=> $t->datetime_field	( "Time of Death" ),
			"game"		=> $t->foreign_key	( "Game", "game", "select" )
		));
	}

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
	public function default_form() {
		return array(
			"Kill" => array(
				"weapon",
				"description",
				"assassin",
				"target",
				"timestamp"
			),
			"Contest" => array(
				"contested",
				"contest"
			)
		);
	}
	
	public static function killboard_list() {
		$db = REGISTRY::get( "db" );
		$sth = $db->prepare( "
			SELECT kills.id as id,
				kills.description 	as description,
				kills.timestamp 	as timestamp,
				assassins.alias		as assassin,
				targets.alias 		as target,
				weapons.name 		as weapon,
				kills.assassin		as aid,
				kills.target		as tid
			FROM kills
			LEFT JOIN agents 		as assassins
				ON kills.assassin 	= assassins.id
			LEFT JOIN agents 		as targets
				ON kills.target 	= targets.id
			LEFT JOIN weapons
				ON kills.weapon 	= weapons.id
			WHERE	kills.game = :curgame
			ORDER BY kills.timestamp DESC
			LIMIT 20
		" );
		
		$sth->bindParam( ":curgame", $gid );
		$curgame = MODEL_GAME::current_games();
		
		if( !is_array( $curgame ) ) {
			return 0;
		}
		else {
			foreach( $curgame as $gid ) {
				$sth->execute();
			
				$res = $sth->fetchAll( PDO::FETCH_ASSOC );
				$kills[ $gid ] = $res;
			}
		}
		return $kills;
	}
}
?>