<?php

class models_weapon extends model
{
	public function __construct( $db )
	{
		$this->db = $db;
		$this->define();
	}
	protected function define()
	{
		$t = $this;
		$t->name		= "weapon";
		$t->nice_name		= "Weapon";
		$t->primary_table	= "weapons";
		$t->primary_key		= "weapons.id";
		
		$t->table( "weapons", array(
			"id"		=> $t->primary_key	(),
			"name"		=> $t->char_field	( "Name" ),
			"class"		=> $t->foreign_key	( "Class", "class", "select" ),
			"customised" 	=> $t->boolean_field	( "Customised" )
		));
	}
	
	protected function default_form()
	{
		return array(
			"Weapon" => array(
				"name",
				"class",
				"customised"
			)
		);
	}
}

?>