<?php

Yii::import('zii.widgets.CBreadcrumbs');

class CBreadcrumbsExt extends CBreadcrumbs {

    /**
     * 提示文字
     * @var string
     */
    public $hintText = '您当前所在位置：';

    public function run() {
        if (empty($this->links))
            return;

        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";
        $links = array();
        if ($this->homeLink === null)
            $links[] = CHtml::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
        else if ($this->homeLink !== false)
            $links[] = $this->homeLink;
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url)) {
                if (!empty($label)) {
                    $links[] = CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
                }
            } else {
                $links[] = '<span>' . ($this->encodeLabel ? CHtml::encode($url) : $url) . '</span>';
            }
        }
        if ($this->hintText) {
            echo $this->hintText;
        }
        echo implode($this->separator, $links);
        echo CHtml::closeTag($this->tagName);
    }

}