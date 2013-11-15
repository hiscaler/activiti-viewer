<?php

$this->widget('zii.widgets.CMenu', array(
    'items' => $this->items,
    'encodeLabel' => false,
    'htmlOptions' => array('class' => 'clearfix'),
    'firstItemCssClass' => 'first',
    'lastItemCssClass' => 'last',
));
