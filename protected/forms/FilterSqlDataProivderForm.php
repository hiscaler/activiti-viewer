<?php

class FilterSqlDataProivderForm extends CFormModel {

    public $filters = array();

    /**
     * Override magic getter for filters
     */
    public function __get($name) {
        if (!array_key_exists($name, $this->filters)) {
            $this->filters[$name] = null;
        }

        return $this->filters[$name];
    }

    /**
     * 获取搜索条件
     * @return array
     */
    public function getConditions() {
        $dbConnection = Yii::app()->db;
        $conditions = array();
        foreach ($this->filters as $column => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $conditions[] = $dbConnection->quoteColumnName($column) . ' = ' . $dbConnection->quoteValue($value);
            }
        }

        return $conditions;
    }

}
