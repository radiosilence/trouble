<?php

class controller_view extends controller
{
	public function index( $args )
	{
	}
	public function agent( $args )
	{
		echo "bugger";
		$agent = $this->viewer_init( "agent", $args[ "id" ] );
		print_r( $agent );
	}
	private function viewer_init( $type, $id )
	{
		$db = $this->database();
		MODEL::set_db( $db );
		$model = MODEL::create( $type );
		$model->load( $args[ "id" ] );
		return $model;
	}
}
?>