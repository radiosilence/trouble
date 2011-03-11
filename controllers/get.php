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
        $results = \Core\Storage::container()
            ->get_storage('Weapon')
            ->fetch(array(
                'order' => new \Core\Order('name')
            ));

    	$weapons = \Trouble\Weapon::mapper()
	        ->get_list($results);

        $json = array();
        foreach($weapons as $weapon) {
            $json[] = array(
                'id' => $weapon['id'],
                'name' => $weapon['name']
            );   
        }
    	echo json_encode($json);
        
    }
}