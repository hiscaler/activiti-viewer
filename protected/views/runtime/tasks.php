<?php

//CVarDumper::dump(array_merge(Activiti::getTableViewColumns('ACT_RU_TASK'), array()), 1111, true);
//exit;
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
                    'url' => 'Yii::app()->controller->createUrl("runtime/variables", array("EXECUTION_ID_" => $data["EXECUTION_ID_"]))',
                ),
            ),
            'headerHtmlOptions' => array('class' => 'actions btn-1'),
            'htmlOptions' => array('class' => 'actions btn-1'),
        )
    )),
));
