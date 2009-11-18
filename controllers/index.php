<?php

class controller_index extends controller
{
	public function index( $args )
	{
		$view 	= new view();
		$db 	= $this->database();
			
		$this->load_locale( "lang" );
		$g = new model_game( $db );
		
//		$g->assign_targets();
foreach( $g->agents as $agent )
{
		$g->kill_agent( $agent );	
}
		echo "\neventual hunter list\n";
		print_r( $g->hunters );
		$view->set( "games", MODEL_KILL::killboard_list() );		
		$view->set( "page_title", L_PAGE_TITLE );
		$view->set( "site_name", L_SITE_NAME );
		$view->show( "home" );
	}
	
	public function hi( $args )
	{
	}
}

?>