<?php

class controller_index extends controller
{
	public function index( $args )
	{
		$view 	= new view( $this->registry );
		$db 	= $this->database();
		
		$this->load_locale( "lang" );
		
		MODEL::set_db( $db );
		$kills = array();
		$kill = MODEL::create( "kill" );
		$kill->load( 1 );
		$kill->save();		
		$view->set( "kills", $kills );
		$view->set( "page_title", L_PAGE_TITLE );
		$view->set( "site_name", L_SITE_NAME );
		$view->show( "home" );
	}
}

?>