<?php

Yii::import('zii.widgets.CListView');

class CListViewExt extends CListView {

    public $cssFile = false;
    public $pager = array(
        'class' => 'ext.CExts.CLinkPagerExt',
    );

}
