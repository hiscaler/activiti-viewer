<?php

/**
 * 顶部菜单
 */
class HeaderMainMenu extends DbWidget {

    public function getItems() {
        $controller = $this->controller;
        $controllerName = $controller->id;
        $exucutionId = Yii::app()->request->getQuery('EXECUTION_ID_');
        return array(
            array(
                'label' => 'Repository',
                'url' => array('repository/procDef'),
                'active' => $controllerName == 'repository',
            ),
            array(
                'label' => 'Runtime',
                'url' => array('runtime/'),
                'active' => $controllerName == 'runtime',
            ),
            array(
                'label' => 'History',
                'url' => array('history/'),
                'active' => $controllerName == 'history',
            ),
            array(
                'label' => 'Settings',
                'url' => array('settings/index'),
                'active' => $controllerName == 'settings'
            ),
        );
    }

    public function run() {
        $this->render('_tabs');
    }

}
