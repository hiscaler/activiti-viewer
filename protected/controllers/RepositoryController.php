<?php

class RepositoryController extends Controller {

    public $layout = 'repository';
    public $title = 'Repository';
    public $defaultAction = 'procDef';

    public function actionProcDef() {
        $this->title = 'Repository Process Def';
        $this->table = 'ACT_RE_PROCDEF';
        $where = '';
        $filterForm = new FilterSqlDataProivderForm;
        if (isset($_GET['FilterSqlDataProivderForm'])) {
            $filterForm->filters = $_GET['FilterSqlDataProivderForm'];
            $conditions = $filterForm->getConditions();
            if ($conditions) {
                $where = ' WHERE ' . implode(' AND ', $conditions);
            }
        }
        $sql = 'SELECT * FROM ACT_RE_PROCDEF' . $where;
        $count = $this->dbConnection->createCommand('SELECT COUNT(*) FROM ACT_RE_PROCDEF' . $where)->queryScalar();
        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'ID_',
            'totalItemCount' => $count,
            'sort' => array(
                'defaultOrder' => 'VERSION_ DESC',
            ),
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));

        $this->render('procDef', array(
            'dataProvider' => $dataProvider,
            'filterForm' => $filterForm,
        ));
    }

}
