<?php

class HistoryController extends Controller {

    public $layout = 'history';
    public $title = 'History';
    public $defaultAction = 'tasks';

    public function actionTasks() {
        $this->title = 'History Tasks';
        $this->table = 'ACT_HI_TASKINST';
        $where = '';
        $filterForm = new FilterSqlDataProivderForm;
        if (isset($_GET['FilterSqlDataProivderForm'])) {
            $filterForm->filters = $_GET['FilterSqlDataProivderForm'];
            $conditions = $filterForm->getConditions();
            if ($conditions) {
                $where = ' WHERE ' . implode(' AND ', $conditions);
            }
        }
        $sql = 'SELECT * FROM ACT_HI_TASKINST' . $where;
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_HI_TASKINST' . $where)->queryScalar();
        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'ID_',
            'sort' => array(
                'defaultOrder' => 'START_TIME_ DESC',
            ),
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));

        $this->render('tasks', array(
            'dataProvider' => $dataProvider,
            'filterForm' => $filterForm,
        ));
    }

    public function actionVariables($EXECUTION_ID_ = null) {
        $this->title = 'History Variables';
        $this->table = 'ACT_HI_VARINST';
        $conditions = '';
        if (!empty($EXECUTION_ID_)) {
            $conditions = ' WHERE EXECUTION_ID_ = ' . $this->dbConnection->quoteValue($EXECUTION_ID_);
            $this->title .= " [ Execution ID {$EXECUTION_ID_} ]";
        }
        $sql = 'SELECT * FROM ACT_HI_VARINST' . $conditions;
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_HI_VARINST' . $conditions)->queryScalar();
        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'ID_',
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => (empty($conditions)) ? self::PAGE_SIZE : $count,
            ),
        ));

        $this->render('/common/defaultGrid', array(
            'dataProvider' => $dataProvider,
        ));
    }

}
