<?php

/*
 * This file is part of trouble.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble\Wizards;

import('core.wizarding');
class NewAgent extends \Core\Wizard {
    protected $step_list = array(
        'NewAgentStepOne'
    );
}

class NewAgentStepOne extends \Core\WizardStep {
    protected $form_data = array(
        'Please enter your desired alias.',
        'alias' => array(
            'type' => 'text',
            'class' => 'title',
            'name' => 'Alias'
        )
    );
}