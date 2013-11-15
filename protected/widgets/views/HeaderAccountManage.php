<ul>
    <?php
    foreach ($this->items as $item):
        $cssClass = isset($item['class']) ? ' class="' . $item['class'] . '"' : '';
        ?>
        <li<?php echo $cssClass; ?>>
            <?php
            $visible = isset($item['visible']) ? $item['visible'] : true;
            if ($visible) {
                echo CHtml::link($item['label'], $item['url']);
                $children = isset($item['items']) ? $item['items'] : array();
                if ($children) {
                    $output = array(
                        '<li class="avatar">' . CHtml::image(Yii::app()->user->getState('avatar')) . '</li>'
                    );
                    foreach ($children as $child) {
                        $output[] = '<li>' . CHtml::link($child['label'], $child['url'], isset($child['htmlOptions']) ? $child['htmlOptions'] : array()) . '</li>';
                    }
                    $output[] = '<li class="email">' . Yii::app()->user->getState('email') . '</li>';
                    echo '<ul style="display: none;">' . implode('', $output) . '</ul>';
                }
            }
            ?>
        </li>
    <?php endforeach; ?>
</ul>