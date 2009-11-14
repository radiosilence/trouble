<?php

class controller_view extends controller
{
	public function index( $args )
	{}
	public function agent( $args )
	{
		$agent = $this->viewer_init( "agent", $args[ "id" ] );
		//$agent->available_properties();	
	}
	private function viewer_init( $type, $id )
	{
		$db = $this->database();
		$model = "model_" . $type;
		$model = new $model( $db );
		$model->load( $id );
				
		return $model;
	}
}
?>