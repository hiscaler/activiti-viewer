<?php $this->beginContent('/layouts/main'); ?>

<div class="layout grid-s6m0e0">
    <div class="col-main">
        <div class="main-wrap">
            <?php if (!empty($this->title)): ?>
                <div class="inner-body-header">
                    <?php echo $this->title; ?>
                    <span>
                        <?php echo CHtml::link(CHtml::image($this->staticUrl . '/images/setting.png'), array('settings/index', 'table' => $this->table)); ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="container">
                <div class="inner">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sub">
        <?php $this->widget('application.widgets.controlPanel.HistoryControlPanel'); ?>
    </div>
</div>

<?php $this->endContent(); ?>