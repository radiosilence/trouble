<?php

class controller_index extends controller
{
	public function index( $args )
	{
		$view = new view( $this->registry );
		
		$this->load_locale( "lang" );
		
		$kills = array();
		$view->set( "kills", $kills );
		$view->set( "page_title", L_PAGE_TITLE );
		$view->set( "site_name", L_SITE_NAME );
		$view->show( "home" );
	}
}

?>