<?php
class model_game extends model
{
	private $agents;
	private $targets;
	private $hunters;
	public function __construct( $db, $id = 0 )
	{
		$this->db = $db;
		
		if( $id > 0 )
		{
			$this->load( $id );
			$this->load_game();
		}
		
		# Target list would be gotten from db WHERE players.games = this game.id etc or some shit
		$this->agents = array(
			"Adam",
			"Boris",
			"Caroline",
			"Deiniol",
			"Edward",
			"Francesca",
			"Gunther",
			"Harriet",
			"Imogen",
			"James",
			"Karl",
			"Louis",
			"Mike",
			"Norman",
			"Oscar",
			"Paris",
			"Quentin",
			"Ralph",
			"Steven",
			"Tomas",
			"Urma",
			"Vera",
			"Xandria",
			"Yusef",
			"Zacharia"
/**/
		);
		# Also they would be a buttload of IDs, not fucking names. Fuck.
		
		$this->targets = json_decode( '{"Edward":["Adam","Boris","Zacharia"],"Gunther":["Adam","Louis","Ralph"],"Steven":["Adam","Imogen","Norman"],"Francesca":["Boris","Deiniol","Paris"],"Urma":["Boris","Gunther","Karl"],"Zacharia":["Caroline","Vera","Xandria"],"Oscar":["Caroline","Harriet","Quentin"],"Norman":["Caroline","Tomas","Xandria"],"Paris":["Deiniol","James","Norman"],"Vera":["Deiniol","Oscar","Steven"],"Caroline":["Edward","Tomas","Yusef"],"Quentin":["Edward","Harriet","Yusef"],"Adam":["Edward","James","Mike"],"Boris":["Francesca","James","Oscar"],"Xandria":["Francesca","Norman","Ralph"],"Deiniol":["Francesca","Urma","Zacharia"],"Ralph":["Gunther","Karl","Quentin"],"Tomas":["Gunther","Mike","Oscar"],"Mike":["Harriet","Louis","Zacharia"],"Karl":["Imogen","Urma","Vera"],"Louis":["Imogen","Ralph","Yusef"],"Yusef":["Karl","Quentin","Vera"],"Harriet":["Louis","Paris","Urma"],"James":["Mike","Paris","Steven"],"Imogen":["Steven","Tomas","Xandria"]}', 1 );
		
		$this->hunters = json_decode( '{"Adam":["Edward","Gunther","Steven"],"Boris":["Edward","Francesca","Urma"],"Caroline":["Zacharia","Oscar","Norman"],"Deiniol":["Paris","Vera","Francesca"],"Edward":["Caroline","Quentin","Adam"],"Francesca":["Boris","Xandria","Deiniol"],"Gunther":["Ralph","Tomas","Urma"],"Harriet":["Mike","Oscar","Quentin"],"Imogen":["Steven","Karl","Louis"],"James":["Adam","Paris","Boris"],"Karl":["Urma","Yusef","Ralph"],"Louis":["Mike","Harriet","Gunther"],"Mike":["James","Adam","Tomas"],"Norman":["Xandria","Steven","Paris"],"Oscar":["Vera","Tomas","Boris"],"Paris":["James","Francesca","Harriet"],"Quentin":["Oscar","Yusef","Ralph"],"Ralph":["Louis","Xandria","Gunther"],"Steven":["James","Imogen","Vera"],"Tomas":["Norman","Imogen","Caroline"],"Urma":["Deiniol","Harriet","Karl"],"Vera":["Zacharia","Yusef","Karl"],"Xandria":["Imogen","Zacharia","Norman"],"Yusef":["Louis","Caroline","Quentin"],"Zacharia":["Mike","Deiniol","Edward"]}', 1 );

	}
	
	protected function define()
	{
		$t = $this;
		
		$t->model_name = "game";
		$t->nice_name = "Game";
		
		$t->primary_table = "games";
		$t->primary_key = "games.id";
		
		$t->table( "games", array(
			"id"		=> $t->primary_key	(),
			"start_date"	=> $t->datetime_field	( "Start Date" ),
			"end_date"	=> $t->datetime_field	( "End Date" ),
			"signup_date"	=> $t->datetime_field	( "Final Signup Date" ),
			"location"	=> $t->text_field	( "Location" ),
			"victor"	=> $t->foreign_key	( "Victorious Agent", "agent", "search" ),
			"description"	=> $t->text_field	( "Description" ),
			"finalised"	=> $t->boolean_field	( "Finalised" ),
			"entry_fee"	=> $t->float_field	( "Entry Fee" )
		));
	}
	
	public function default_form()
	{
		return array(
			"Dates" => array(
				"signup_date",
				"start_date",
				"end_date"
			),
			"Info" => array(
				"location",
				"description",
				"entry_fee"
			),
			"Status" => array(
				"finalised",
				"victor",
				"entry_fee"
			)
		);
	}
	
	/**
	 * Asks the database what games are currently running.
	 */
	public static function current_games( $db = 0 )
	{
		if( !$db )
		{
			$db = REGISTRY::get( "db" );
		}
		
		$sth = $db->prepare( "
			SELECT 	id
			FROM 	games
			WHERE	start_date < CURRENT_TIMESTAMP()
			AND	end_date > CURRENT_TIMESTAMP()
		" );
		
		$sth->execute();
		
		if( $sth->rowCount() > 0 )
		{
			return $sth->fetchAll( PDO::FETCH_COLUMN, 0 );
		}
		else
		{
			return 0;
		}
	}
	
	private function load_game()
	{
		$id = $this->id;
		# Get the living and dead agents from the db.
		# Get the list of targets/assassins.
	}
	
	public function assign_targets()
	{
		if( count( $this->agents ) < 4 )
		{
			die( "Not enough agents for a 3-agent game." );
		}
		$target_pool = array_merge( $this->agents, $this->agents, $this->agents);
		$agents = $this->agents;
		$outer = 0;
		do
		{
			shuffle( $target_pool );
			for( $i = 0; $i < count( $agents ); $i++ )
			{
				$assassins[ $agents[ $i ] ] = array();
				$loops = 0;
				for( $x = 0; $x < 3; $x++ )
				{
					do
					{
						$ok = 0;
						$target = array_pop( $target_pool );
						if( ( $target == $agents[ $i ]
							|| in_array( $target, $assassins[ $agents[ $i ] ] )
							&& $outer < 20
							&& $loops < 20 )
						)
						{
							$loops++;
							array_push( $target_pool, $target );
							shuffle( $target_pool );
						}
						else
						{
							$loops 				= 0;
							$hunters[ $agents[ $i ] ][]	= $target;
							$targets[ $target ][]		= $agents[ $i ];
							$ok 				= 1;
						}
					}
					while ( !$ok && $loops <= 20 );
				}
			}
			
			$outer++;
		}
		while ( !$ok && $outer <= 20 );
		
		if( $outer > 20 )
		{
			die( "Super unlucky target allocation error or target allocation impossible." );
		}
		
		$this->targets = $targets;
		$this->hunters = $hunters;
		
/*		echo json_encode( $this->targets );
		echo "\nassassins:";
		echo json_encode( $this->assassins );
		echo "\n\n";*/
		return $targets;
	}
	
	public function kill_agent( $target )
	{
		echo "Killing $target, that fucker!\n";
		print_r( $this->hunters );
		
		$this->agents[ "living" ] = array( 1, 1, 1, 1 ); //debug
		if( count( $this->agents[ "living" ] ) > 3 )
		{
			# 1. Target's targets as a list.
			$ts = $this->get_targets( $target );
			# 2. Find target's assassins as a list.
			$hs = $this->get_hunters( $target );
			# 3. Assign target's targets to hunters.
			foreach( $ts as $t )
			{
				$h = $hs[ 0 ];
				$ht = $this->get_targets( $h );
				
				if( $t != $h && !in_array( $t, $ht ) )
				{
					$this->replace_target( $h, $target, $t );
				}
				else
				{
					# BAD
				}
				array_shift( $hs );
			}

			# 4. Make sure lists are updated.
			# 5. Take the agent out of the target list and hunter list.
			unset( $this->hunters[ $target ] );
			unset( $this->targets[ $target ] );		
		}
		else
		{
			# All on all code.
		}
		print_r( $this->hunters );
	}
	
	private function replace_target( $agent, $old, $new )
	{
		# Replace the target's hunter with new one.
		$th = $this->targets[ $new ];
		$k  = array_search( $old, $th );
		$this->targets[ $new ][ $k ] = $agent;
				
		# Replace the hunter's target with new one.
		$ht = $this->hunters[ $agent ];
		$k = array_search( $old, $ht );
		$this->hunters[ $agent ][ $k ] = $new;
	}
	private function get_targets( $agent )
	{
		return $this->hunters[ $agent ];
	}
	
	private function get_hunters( $agent )
	{
		return $this->targets[ $agent ];
	}
}
?>