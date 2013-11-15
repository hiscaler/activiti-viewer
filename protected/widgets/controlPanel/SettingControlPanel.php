<?php

/**
 * Setting
 */
class SettingControlPanel extends CWidget {

    public $title = 'Setting';

    public function getTables() {
        $tables = Yii::app()->db->schema->getTableNames();
        asort($tables);
        return $tables;
    }

    public function getItems() {
        $items = array();
        foreach ($this->tables as $table) {
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
