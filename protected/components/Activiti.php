<?php

class Activiti {

    public static function getTableColumns($table) {
        if (!empty($table)) {
            return Yii::app()->db->schema->getTable($table)->columnNames;
        } else {
            return array();
        }
    }

    public static function getTableViewColumns($table) {
        $cacheKey = Yii::app()->request->userHostAddress . $table;
        $cache = Yii::app()->cache->get($cacheKey);
        if ($cache) {
            return $cache;
        } else {
            return self::getTableColumns($table);
        }
    }

    public static function settingViewColumn($table, $column, $action = 'add') {
        $viewColumns = self::getTableViewColumns($table);
        if ($action == 'add') {
            $viewColumns[] = $column;
        } else if ($action == 'remove') {
            foreach ($viewColumns as $key => $value) {
                if ($value == $column) {
                    unset($viewColumns[$key]);
                }
            }
        }
        $cacheKey = Yii::app()->request->userHostAddress . $table;
        Yii::app()->cache->set($cacheKey, $viewColumns);
    }

}
