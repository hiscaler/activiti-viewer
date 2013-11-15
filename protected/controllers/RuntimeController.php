<?php

class RuntimeController extends Controller {

    public $layout = 'runtime';
    public $title = 'Runtime';
    public $defaultAction = 'executions';

    public function actionExecutions() {
        $this->title = 'Runtime Executions';
        $this->table = 'ACT_RU_EXECUTION';

        $sql = 'SELECT * FROM ACT_RU_EXECUTION';
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_RU_TASK')->queryScalar();
        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'ID_',
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));

        $this->render('/common/defaultGrid', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionTasks($ID_ = null, $PROC_DEF_ID_ = null) {
        $this->title = 'Runtime Tasks';
        $this->table = 'ACT_RU_TASK';
        $filterForm = new FilterSqlDataProivderForm;
        if (isset($_GET['FilterSqlDataProivderForm'])) {
            $filterForm->filters = $_GET['FilterSqlDataProivderForm'];
            $conditions = $filterForm->getConditions();
        } else {
            $conditions = array();
            if ($ID_) {
                $conditions[] = 'ID_ = ' . $this->dbConnection->quoteValue($ID_);
            }
            if ($PROC_DEF_ID_) {
                $conditions[] = 'PROC_DEF_ID_ = ' . $this->dbConnection->quoteValue($PROC_DEF_ID_);
                $this->title .= " [ PROC DEF ID {$PROC_DEF_ID_} ]";
            }
        }

        if ($conditions) {
            $where = ' WHERE ' . implode(' AND ', $conditions);
        } else {
            $where = '';
        }

        $sql = 'SELECT * FROM ACT_RU_TASK' . $where;
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_RU_TASK' . $where)->queryScalar();
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

        $this->render('tasks', array(
            'dataProvider' => $dataProvider,
            'filterForm' => $filterForm,
        ));
    }

    public function actionVariables($EXECUTION_ID_ = null) {
        $this->title = 'Runtime Variables';
        $this->table = 'ACT_RU_VARIABLE';
        $conditions = '';
        if (!empty($EXECUTION_ID_)) {
            $conditions = ' WHERE EXECUTION_ID_ = ' . $this->dbConnection->quoteValue($EXECUTION_ID_);
            $this->title .= " [ Execution ID {$EXECUTION_ID_} ]";
        }
        $sql = 'SELECT * FROM ACT_RU_VARIABLE' . $conditions;
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_RU_VARIABLE' . $conditions)->queryScalar();
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
