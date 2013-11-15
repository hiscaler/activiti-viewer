<?php

$this->widget('ext.CExts.CGridViewExt', array(
    'id' => 'data-grid',
    'dataProvider' => $dataProvider,
    'filter' => $filterForm,
    'columns' => array_merge(Activiti::getTableViewColumns($this->table), array(
        array(
            'class' => 'ext.CExts.CButtonColumnExt',
            'template' => '{runtimeTasks}',
            'buttons' => array(
                'runtimeTasks' => array(
                    'label' => 'Runtime Tasks',
                    'url' => 'Yii::app()->controller->createUrl("runtime/tasks", array("PROC_DEF_ID_" => $data["ID_"]))',
                ),
            ),
            'headerHtmlOptions' => array('class' => 'actions btn-1'),
            'htmlOptions' => array('class' => 'actions btn-1'),
        ),
    )),
));
