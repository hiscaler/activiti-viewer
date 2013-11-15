<?php

$this->widget('ext.CExts.CGridViewExt', array(
    'id' => 'data-grid',
    'dataProvider' => $dataProvider,
    'filter' => $filterForm,
    'columns' => array_merge(Activiti::getTableViewColumns($this->table), array(
        array(
            'class' => 'ext.CExts.CButtonColumnExt',
            'template' => '{variables}',
            'buttons' => array(
                'variables' => array(
                    'label' => 'Variables',
                    'url' => 'Yii::app()->controller->createUrl("history/variables", array("EXECUTION_ID_" => $data["EXECUTION_ID_"]))',
                ),
            ),
            'headerHtmlOptions' => array('class' => 'actions btn-1'),
            'htmlOptions' => array('class' => 'actions btn-1'),
        ),
    )),
));
