<?php

/**
 * 记录操作按钮部件
 */
class MenuButtons extends CWidget {

    public $outerTag = 'ul';
    public $outerHtmlOptions = array('class' => 'tasks');
    public $innerTag = 'li';
    public $innerHtmlOptions = array();
    public $items = array();

    public function run() {        
        echo CHtml::openTag('div', array('id' => 'menu-buttons'));
        echo CHtml::openTag($this->outerTag, $this->outerHtmlOptions);
        foreach ($this->items as $item) {
            if (isset($item['visiable']) && $item['visiable'] === false) {
                continue;
            }
            $linkHtmlOptions = (isset($item['htmlOptions'])) ? $item['htmlOptions'] : array();
            if ($item['url'] == '#') {
                $item['url'] = 'javascript: void(0);';
                $this->innerHtmlOptions['class'] = 'search-button';
            }
            echo CHtml::openTag($this->innerTag, $this->innerHtmlOptions + $linkHtmlOptions);
            echo CHtml::link("<em>{$item['label']}</em>", $item['url'], $linkHtmlOptions);
            echo CHtml::closeTag($this->innerTag);
        }
        echo CHtml::closeTag($this->outerTag);
        echo CHtml::closeTag('div');
        Yii::app()->clientScript->registerScript('search-button-toggle', '$(".search-button").click(function(){$(".search-button").toggleClass("active");return false;});');
    }

}
