<?php

namespace Controllers;

import('controllers.standard_page');
import('core.exceptions');

class Get extends \Controllers\StandardPage {
    public function __construct($args) {
        parent::__construct($args);
        $tok = ($_POST['tok'] ? $_POST['tok'] : $_GET['tok']);
        if($this->_session->get_tok() != $tok) {
            throw new \Core\HTTPError(401, $this->_args['method']);
        }
    }
    public function index() {}
    public function weapon_list() {
    	import('trouble.weapon');
    	$results = \Trouble\Weapon::mapper()
	        ->attach_storage(\Core\Storage::container()
	            ->get_storage('Weapon'))
	        ->get_list(array(
                'order' => new \Core\Order('name')
            ));
        $json = array();
        foreach($results as $result) {
            $json[] = array(
                'id' => $result['id'],
                'name' => $result['name']
            );   
        }
    	echo json_encode($json);
        
    }
}