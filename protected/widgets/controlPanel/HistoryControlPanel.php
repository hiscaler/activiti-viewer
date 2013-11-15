<?php

/**
 * History
 */
class HistoryControlPanel extends CWidget {

    public $title = 'Runtime';

    public function getItems() {
        return array(
            array(
                'label' => 'Tasks',
                'url' => array('history/tasks'),
            ),
            array(
                'label' => 'Variables',
                'url' => array('history/variables'),
            ),
        );
    }

    public function run() {
        $this->render('_controlPanel');
    }

}
