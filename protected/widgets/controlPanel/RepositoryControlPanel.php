<?php

/**
 * Repository
 */
class RepositoryControlPanel extends CWidget {

    public $title = 'Runtime';

    public function getItems() {
        return array(
            array(
                'label' => 'Proc Def',
                'url' => array('repository/procDef'),
            ),
        );
    }

    public function run() {
        $this->render('_controlPanel');
    }

}
