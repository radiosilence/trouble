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

class Agent {	
	public function __construct( $db ) {
		$this->db = $db;
		$this->define();
	}
	
	protected function define() {
		$t = $this;
		
		$t->model_name		= "agent";
		$t->nice_name		= "Agent";
		
		$t->primary_table	= "agents";
		$t->primary_key 	= "agents.id";
		
		$t->table( "agents", array(
			"id"		=> $t->primary_key	(),
			"fullname"	=> $t->char_field	( "Full Name" ),
			"alias"		=> $t->char_field	( "Alias" ),
			"email"		=> $t->char_field	( "E-Mail", array( "validation" => "email" )),
			"phone"		=> $t->char_field	( "Phone Number" ),
			"address"	=> $t->text_field	( "Address" ),
			"course"	=> $t->char_field	( "Degree" ),
			"societies"	=> $t->text_field	( "Societies" ),
			"clubs"		=> $t->text_field	( "Bars, Clubs & Pubs" ),
			"timetable"	=> $t->text_field	( "Timetable (+Times and Locations)" ),
			"kill_count"	=> $t->integer_field	( "Frags" ),
			"death_count"	=> $t->integer_field	( "Deaths" ),
		));
	}
	
	public function default_form() {
		return array(
			"Contact Information" => array(
				"fullname" => "title",
				"alias",
				"email",
				"phone",
				"address"
			),
			"Profile" => array(
				"course",
				"societies",
				"clubs",
				"timetable"
			)
		);	
	}
}

?>