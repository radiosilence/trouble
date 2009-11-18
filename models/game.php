<?php
class model_game extends model
{
	public $agents;
	private $targets;
	public $hunters;
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
			"Winston",
			"Xandria",
			"Yusef",
			"Zacharia"
/**/
		);
		# Also they would be a buttload of IDs, not fucking names. Fuck.
		
		$this->targets = json_decode( '{"Edward":["Adam","Boris","Zacharia"],"Gunther":["Adam","Louis","Ralph"],"Steven":["Adam","Imogen","Norman"],"Francesca":["Boris","Deiniol","Paris"],"Urma":["Boris","Gunther","Karl"],"Zacharia":["Caroline","Vera","Xandria"],"Oscar":["Caroline","Harriet","Quentin"],"Norman":["Caroline","Tomas","Xandria"],"Paris":["Deiniol","James","Norman"],"Vera":["Deiniol","Oscar","Steven"],"Caroline":["Edward","Tomas","Yusef"],"Quentin":["Edward","Harriet","Yusef"],"Adam":["Edward","James","Mike"],"Boris":["Francesca","James","Oscar"],"Xandria":["Francesca","Norman","Ralph"],"Deiniol":["Francesca","Urma","Zacharia"],"Ralph":["Gunther","Karl","Quentin"],"Tomas":["Gunther","Mike","Oscar"],"Mike":["Harriet","Louis","Zacharia"],"Karl":["Imogen","Urma","Vera"],"Louis":["Imogen","Ralph","Yusef"],"Yusef":["Karl","Quentin","Vera"],"Harriet":["Louis","Paris","Urma"],"James":["Mike","Paris","Steven"],"Imogen":["Steven","Tomas","Xandria"]}', 1 );
		
		$this->hunters = json_decode( '{"Adam":["Edward","Gunther","Steven"],"Boris":["Edward","Francesca","Urma"],"Caroline":["Zacharia","Oscar","Norman"],"Deiniol":["Paris","Vera","Francesca"],"Edward":["Caroline","Quentin","Adam"],"Francesca":["Boris","Xandria","Deiniol"],"Gunther":["Ralph","Tomas","Urma"],"Harriet":["Mike","Oscar","Quentin"],"Imogen":["Steven","Karl","Louis"],"James":["Adam","Paris","Boris"],"Karl":["Urma","Yusef","Ralph"],"Louis":["Mike","Harriet","Gunther"],"Mike":["James","Adam","Tomas"],"Norman":["Xandria","Steven","Paris"],"Oscar":["Vera","Tomas","Boris"],"Paris":["James","Francesca","Harriet"],"Quentin":["Oscar","Yusef","Ralph"],"Ralph":["Louis","Xandria","Gunther"],"Steven":["James","Imogen","Vera"],"Tomas":["Norman","Imogen","Caroline"],"Urma":["Deiniol","Harriet","Karl"],"Vera":["Zacharia","Yusef","Karl"],"Xandria":["Imogen","Zacharia","Norman"],"Yusef":["Louis","Caroline","Quentin"],"Zacharia":["Mike","Deiniol","Edward"]}', 1 );
		
		$this->assign_targets();
		print_r( $this->hunters );

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
		$target_pool = array( $this->agents, $this->agents, $this->agents );
		$agents = $this->agents;
		$outer = 0;

		foreach( $target_pool as $pool )
		{
			shuffle( $pool );
		}
		foreach( $agents as $i => $agent )
		{
			$hunters[ $agent ] = array();
			for( $p = 0; $p < 3; $p++ )
			{
				$tmp_pool = array();
				foreach( $target_pool[ $p ] as $k => $fish )
				{
					if( $fish != $agent && !in_array( $fish, $hunters[ $agent ] ) )
					{
						$tmp_pool[] = $k;
					}
				}
				echo $luckyfish = rand( 0, count( $tmp_pool ) - 1 );
				$fish = $target_pool[ $p ][ $tmp_pool[ $luckyfish ] ];
				echo "[$fish]";
				$hunters[ $agent ][] = $fish;
				$targets[ $fish ][]  = $agent;
				unset( $target_pool[ $p ][ $tmp_pool[ $luckyfish ] ] );
			}
		}
		
		if( $outer > 20 )
		{
			die( "Super unlucky target allocation error or target allocation impossible." );
		}
		print_r( $hunters );
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
		if( count( $this->hunters ) > 4 )
		{
			
			echo "\n\nREPLACEMENT CYCLE FOR $target ITERATION $iters\n\n";
			# 1. Target's targets as a list.
			$ts = $this->get_targets( $target );
			shuffle( $ts );
			# 2. Find target's assassins as a list.
			$hs = $this->get_hunters( $target );
			# 3. Assign target's targets to hunters.
			$this->target_hunter_match( $ts, $hs, $target );
			$replacements = array();
			
			for( $i = 0; $i < count( $ts ); $i++ )
			{
				$this->replace_target( $hs[ $i ], $target, $ts[ $i ] );
			}
				
			# 4. Make sure lists are updated.
			# 5. Take the agent out of the target list and hunter list.
			unset( $this->hunters[ $target ] );
			unset( $this->targets[ $target ] );		
		}
		else
		{
			# All on all code.
			print_r( $this->hunters );
			echo( "\n!!!!!!!!!ALL ON ALL!!!!!!!!\n" );
		}
	}
	
	private function replace_target( $agent, $old, $new )
	{
		# Replace the target's hunter with new one.
		$th = $this->targets[ $new ];
		if( !is_array( $th ) )
		{
			die("no targets for $new!!\n");
		}
		$k  = array_search( $old, $th );
		$this->targets[ $new ][ $k ] = $agent;
		echo "{$agent} <- $old <- $new\n";
		
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
	
	private function target_hunter_match( &$ts, &$hs, $ag )
	{
		echo "\n================\n$ag hunters\n";
		print_r( $this->get_hunters( $ag ) );
		echo "\n$ag targets\n";
		print_r( $this->get_targets( $ag ) );
		echo "$ag hunters targets\n";
		foreach( $this->get_hunters( $ag ) as $v )
		{
			echo "\n$ag/$v targets\n";
			print_r( $this->get_targets( $v ) );
		}
		echo "\n------------\n";
		$ht = array();
		shuffle( $ts );
		$loops = 0;
		
		while( !$ok && $loops < 32 )
		{
			$ok = 1;
			
			echo "$t iteration $loops\n";
			foreach( $ts as $tk => $t )
			{
				foreach( $hs as $hk => $h )
				{
					if( $h != $t && !in_array( $h, $ht ) && !in_array( $t, $this->get_targets( $h )))
					{
						$ht[ $tk ] = $h;
					}
				}
				if( !isset( $ht[ $tk ] ) )
				{
					$ok = 0;
				}
			}
			print_r( $ht );
			# Try again
			shuffle( $hs );
			
			$loops++;
			
			if( $loops > 30 )
			{
				echo "=========LOOP OVERRUN=========";
			}
		}
		$hs = $ht;
	}
}
?>