<?php

namespace Trouble;
import('core.mapping');
import('core.containment');
import('core.hasher');

class Voucher extends \Core\Mapped {
    public static $fields = array('code', 'type', 'active', 'game');

    public function credit_value() {
        if(strpos($this->type) === False) {
            throw new InvalidVoucherError();
        }
        return (int)str_replace('credit', null, $this->type);
    }

    public function spend() {
        $this->active = False;
        $this->save();
        return $this;
    }

    public function generate_code() {
        $hasher = new \Core\Hasher();
        $bad = array('.','/');
        $good = array('5', '7');
        $this->code = implode(
            '-',
            str_split(
                strtoupper(
                    str_replace(
                        $bad,
                        $good,
                        $hasher->gen_chars(7)
                    )
                ),
                3
            )
        );
    }
}
class VoucherMapper extends \Core\Mapper {
    public function create_object($data) {
        return Voucher::create($data);
    }
}
class VoucherContainer extends \Core\MappedContainer {
    public function make_new($type, $game) {
        $v = Voucher::create();
        do {
            $v->generate_code();
            $result = $this->get_by_field('code', $v->code);
        } while($result);

        $v->type = $type;
        $v->game = $game;
        $v->active = True;

        $v->save();
        return $v;
    }

    public function get_valid($code, $type, $game) {
        $pars = array(
            'filters' => array(
                new \Core\Filter('game', $game->id),
                new \Core\Filter('code', $code)
            )
        );
        if($type) {
            array_pop($pars['filters'],
                new \Core\Filter('type', $type));
        }
        $v = $this->get($pars)->{0};
        if(!$v) {
            throw new InvalidVoucherError();
        }
        if(!$v->active) {
            throw new VoucherInactiveError();
        }
        return $v;
    }
}

class VoucherError extends \Core\StandardError {}
class InvalidVoucherError extends VoucherError {}
class VoucherInactiveError extends VoucherError {}