<?php

class SiteController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $sql = 'SELECT * FROM ACT_RU_TASK';
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_RU_TASK')->queryScalar();
        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'ID_',
            'sort' => array(
                'defaultOrder' => 'CREATE_TIME_ DESC',
            ),
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

}
