<?php
/* @var $this Controller */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->staticUrl; ?>/css/application.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->staticUrl; ?>/css/common.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->staticUrl; ?>/css/widget-grid-view.css" media="screen, projection" />
        <?php
        $this->clientScript->registerCoreScript('jquery');
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
        <div id="page-hd">
            <div id="page">
                <!-- Header -->
                <div id="header">
                    <div id="logo"><?php echo CHtml::link(CHtml::image($this->staticUrl . '/images/logo.png'), Yii::app()->homeUrl); ?></div>
                    <div id="main-menu">
                        <?php $this->widget('application.widgets.HeaderMainMenu'); ?>
                    </div>
                    <div id="header-account-manage">
                        <?php // $this->widget('admin.widgets.HeaderAccountManage'); ?>
                    </div>
                </div>
                <!-- // Header -->
            </div>
        </div>
        <div id="page-bd">
            <div class="container">
                <?php echo $content; ?>
            </div>
        </div>
        <div id="page-ft">
            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by abc All Rights Reserved.
            </div>
        </div>
    </body>
</html>