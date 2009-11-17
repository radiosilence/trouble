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
		$g->kill_agent( "Edward" );
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