<?php

class DbWidget extends CWidget {

    protected $dbConnection;
    public $limit = 12;

    public function init() {
        parent::init();
        $this->dbConnection = Yii::app()->db;
    }

}
