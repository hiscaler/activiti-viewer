<?php

$this->widget('ext.CExts.CGridViewExt', array(
    'id' => 'data-grid',
    'dataProvider' => $dataProvider,
    'columns' => Activiti::getTableViewColumns($this->table),
));
