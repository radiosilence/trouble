<?php

class controller_dashboard extends controller
{
	public function index( $args )
	{
		# Get the logged in agent, the games they're playing, their
		# status in each, target information, balance, etc.
		# Find the list of stuff they can do and buy, their kills, etc.
		# Probably want a bunch of SQL that involves pulling this
		# out efficiently, perhaps this could be part of the models,
		# just to do things the "right" way.
		
	}
	
	public function database( $args )
	{
		# Interface for purchasing information.
	}
	
	public function games( $args )
	{
		# Interface for joining games, current games, etc.
	}
}

?>