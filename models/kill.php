<?php

class model_kill extends model
{
	public function __construct( $db )
	{
		$this->db = $db;
		$this->define();
	}

	protected function define()
	{
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
	public function register_kill()
	{
		# 1. Initialise target
		# 2. Set target dead
		# 3. Initialise assassin
		# 4. Increase kill count
		# 5. Assign new target
		# 6. E-mail killer new target?
	}
	public function default_form()
	{
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
	
	public function killboard_list()
	{
		$db = $this->db;
		$sth = $db->prepare( "
			SELECT kills.id as id,
				kills.description 	as description,
				kills.timestamp 	as timestamp,
				assassins.name 		as assassin,
				targets.name 		as target,
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
			ORDER BY kills.timestamp DESC
			LIMIT 20
		" );
		
		$sth->execute();
		
		$kills = $sth->fetchAll( PDO::FETCH_ASSOC );
		
		foreach( $kills as $k => $kill )
		{
			$aurl = new seo_url(
				array(
					"id" => $kill[ "id" ],
					"title" => $kill[ "assassin" ]
				)
			);
			$kills[ $k ][ "aurl" ] = $aurl->url;
			
			$turl = new seo_url(
				array(
					"id" => $kill[ "id" ],
					"title" => $kill[ "target" ]
				)
			);
			$kills[ $k ][ "turl" ] = $turl->url;
		}
		
		return $kills;
	}
}
?>