<?php

class SettingsController extends Controller {

    public $title = 'System Setting';
    public $layout = 'settings';

    public function actionIndex($table = null) {
        if (!empty($table) && in_array($table, Activiti::getTables())) {
            $columns = $this->getTableColumns($table);
            $this->title .= " [ {$table} ]";
        } else {
            throw new CHttpException(400, '无效的请求，请不要重复执行。');
        }

        $this->render('index', array(
            'table' => $table,
            'columns' => $columns,
        ));
    }

    public function getDatabaseStructure($table = null) {
        $structure = array();
        if (!empty($table)) {
            $structure = $this->getTableColumns($table);
        } else {
            foreach ($this->getTables() as $table) {
                $structure[$table] = $this->getTableColumns($table);
            }
        }

        return $structure;
    }

    private function getTableColumns($table) {
        if (!empty($table)) {
            return $this->dbConnection->schema->getTable($table)->columnNames;
        } else {
            return array();
        }
    }

    public function actionToggleColumn() {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {
            $table = $request->getPost('table');
            $column = $request->getPost('column');
            $action = $request->getPost('action');
            if ($table && $column) {
                Activiti::settingViewColumn($table, $column, $action);
            }
        }
    }

}
