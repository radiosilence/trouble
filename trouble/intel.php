<?php

namespace Trouble;

import('core.mapping');


class Intel extends \Core\Mapped {
    public static $fields = array('field', 'name', 'description', 'cost');
}

class IntelMapper extends \Core\Mapper {
    public function create_object($data) {
        return Intel::create($data);
    }
}

class IntelContainer extends \Core\MappedContainer {
    
}

class BuyIntelError extends \Core\Error {}

class OwnedIntel extends \Core\Mapped {
    public static $fields = array('player', 'subject', 'intel');
    public static function validation() {
        return array(
            'intel' => array(
                'type' => 'foreign',
                'class' => '\Trouble\Intel'
            ),
            'subject' => array(
                'type' => 'foreign',
                'class' => '\Trouble\Agent'
            )
        );
    }
    
}

class OwnedIntelMapper extends \Core\Mapper {
    public function create_object($data) {
        return OwnedIntel::create($data);
    }
}

class OwnedIntelContainer extends \Core\MappedContainer {

    public function get_owned_intel($subject, $player) {
        $results = $this->get(array(
                'filters' => array(
                    new \Core\Filter('player', $player['id']),
                    new \Core\Filter('subject', $subject['id'])
                    )
            ));
        $intels = \Trouble\Intel::container()
            ->get(array(
                'order' => new \Core\Order('ord')
            ));
        $data = new \Core\Li();
        foreach($intels as $intel) {
            $res = $results->filter($intel['id'], 'intel')->{0};
            if(!$res) {
                continue;
            }
            $res['intel'] = $intel;
            $res['data'] = $subject[$intel['field']];
            $data->extend($res);
        }
        return $data;
    }}