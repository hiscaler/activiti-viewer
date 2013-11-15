<?php

/**
 * Runtime
 */
class RuntimeControlPanel extends CWidget {

    public $title = 'Runtime';

    public function getItems() {
        return array(
            array(
                'label' => 'Task',
                'url' => array('runtime/tasks'),
            ),
            array(
                'label' => 'Variable',
                'url' => array('runtime/variables'),
            ),
            array(
                'label' => 'Executions',
                'url' => array('runtime/executions'),
            ),
        );
    }

    public function run() {
        $this->render('_controlPanel');
    }

}
