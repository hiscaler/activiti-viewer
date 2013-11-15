<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    const PAGE_SIZE = 16;

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * The relative URL for the application.
     * @var string
     */
    public $baseUrl;

    /**
     * The relative URL for the application static file.
     * @var string
     */
    public $staticUrl;

    /**
     * DB Connection
     * @var CDbConnection
     */
    public $dbConnection;
    public $formatter;
    public $title;
    public $clientScript;
    public $table;

    public function init() {
        parent::init();
        $this->dbConnection = Yii::app()->db;
        $this->baseUrl = Yii::app()->request->baseUrl;
        $this->staticUrl = $this->baseUrl . '/static';
        $this->clientScript = Yii::app()->getClientScript();
    }

}
