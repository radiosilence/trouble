<?php
class model_game extends model
{
	public function __construct( $db )
	{
		$this->db = $db;
	}
	protected function define()
	{
		$t = $this;
		
		$t->name = "game";
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
}
?>