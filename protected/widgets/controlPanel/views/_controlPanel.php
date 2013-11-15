<div class="control-panel">
    <div class="inner">
        <div class="welcome">Activiti</div>
        <div class="title shortcut"><?php echo $this->title; ?></div>
        <div class="shortcuts">
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->items,
                'encodeLabel' => false,
                'htmlOptions' => array('class' => 'clearfix'),
                'firstItemCssClass' => 'first',
                'lastItemCssClass' => 'last',
            ));
            ?>
        </div>
    </div>
</div>