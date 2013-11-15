<?php

/**
 * 顶部菜单操作
 */
class HeaderAccountManage extends CWidget {

    public function getItems() {
        return array(
            array(
                'label' => '<div class="icon header-settings"></div>',
                'url' => array('profile/'),
                'visible' => !Yii::app()->user->isGuest ? true : false,
            ),
            array(
                'label' => '注册',
                'url' => array('passport/register'),
                'visible' => Yii::app()->user->isGuest
            ),
            array(
                'label' => '登录',
                'url' => array('passport/login'),
                'visible' => Yii::app()->user->isGuest,
            ),
            array(
                'label' => Yii::app()->user->name,
                'url' => array('passport/logout'),
                'visible' => !Yii::app()->user->isGuest,
                'class' => 'children',
                'items' => array(
                    array(
                        'label' => '修改密码',
                        'url' => array('passport/changePassword'),
                    ),
                    array(
                        'label' => '帐号设置',
                        'url' => array('profile/settings'),
                    ),
                    array(
                        'label' => '退出登录',
                        'url' => array('passport/logout'),
                        'htmlOptions' => array('onClick' => 'return confirm("您是否确认注销本系统？");'),
                    ),
                ),
            ),
        );
    }

    public function run() {
        $this->render('HeaderAccountManage');
    }

}
