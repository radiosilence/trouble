<?php

class controller_index extends controller
{
	public function index( $args )
	{
		$view 	= new view();
		$db 	= $this->database();

		$id_of_assassin = 1;
		// $db is a pdo database object
		$a = new model_agent( $db );
		$a->load( 1 );
		$t = new model_agent( $db );
		$t->load( $a->target );
	
		$this->load_locale( "lang" );
		
		$kill 	= new model_kill( $db );
		$view->set( "games", $kill->killboard_list() );
		$view->set( "page_title", L_PAGE_TITLE );
		$view->set( "site_name", L_SITE_NAME );
		$view->show( "home" );
	}
}

?>