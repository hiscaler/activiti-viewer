<?php

/**
 * Setting
 */
class SettingControlPanel extends CWidget {

    public $title = 'Setting';

    public function getItems() {
        $items = array();
        foreach (Activiti::getTables() as $table) {
            $items[] = array(
                'label' => $table,
                'url' => array('settings/index', 'table' => $table),
            );
        }

        return $items;
    }

    public function run() {
        $this->render('_controlPanel');
    }

}
