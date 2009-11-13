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
		
		$classes = array(
			"Melee",
			"Ranged",
			"Poison",
			"Special"
		);
		
		$t->table( "weapons", array(
			"id"		=> $t->primary_key	(),
			"name"		=> $t->char_field	( "Name" ),
			"class" 	=> $t->char_field	( "Class", array( "choices" => $classes ) )
			"order" 	=> $t->hidden_field	( "Order" )
		));
	}
	
	protected function default_form()
	{
		return array(
			"Weapon" => array(
				"name",
				"class"
			)
		);
	}
}

?>