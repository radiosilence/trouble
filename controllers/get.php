<?php

namespace Controllers;

import('controllers.standard_page');
import('core.exceptions');

class Get extends \Controllers\StandardPage {
    public function __construct($args) {
        parent::__construct($args);
        $tok = ($_POST['tok'] ? $_POST['tok'] : $_GET['tok']);
        if($this->_session->get_tok() != $tok) {
            //throw new \Core\HTTPError(401, $this->_args['method']);
        }
    }
    public function index() {}
    public function weapon_list() {
    	import('trouble.weapon');
        $json = array();
        $weapons = \Trouble\Weapon::container()
            ->get(array(
                'order' => new \Core\Order('name')
            ));
        foreach($weapons as $weapon) {
            array_push($json, array(
                'id' => $weapon['id'],
                'name' => $weapon['name']
            ));   
        }
    	echo json_encode($json);
        
    }
    public function intel_list() {
        import('trouble.intel');
        $json = array();
        $intels = \Trouble\Intel::container()
            ->get();
        foreach($intels as $intel) {
            array_push($json, array(
                'id' => $intel['id'],
                'name' => sprintf("%s (%d credit%s)",
                    $intel['name'],
                    $intel['cost'],
                    ($intel['cost'] > 1 ? 's' : null)
                )
            ));
        }
        echo json_encode($json);
    }
    public function intel_description() {
        import('trouble.intel');
        import('core.storage');
        echo \Trouble\Intel::container()
            ->get(array(
                'filter' => new \Core\Filter('id', $_POST['field'])
            ))->{0}['description'];
    }
}