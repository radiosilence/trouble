<?php
class model_game extends model
{
	public function __construct( $db )
	{
		$this->db = $db;
	}
	protected function define();
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
	
	public static function current_games()
	{
		$db = $this->db;
		$sth = $db->prepare( "
			SELECT 	id
			FROM 	games
			WHERE	start_date < CURRENT_TIMESTAMP()
			AND	end_date > CURRENT_TIMESTAMP()
		";
	}
}
?>