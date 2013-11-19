<ul class="list">
    <?php foreach ($functions as $name => $params): ?>
        <li>
            <?php echo CHtml::link($name, array('esb', 'serviceName' => $serviceName, 'methodName' => $name)); ?>
        </li>
    <?php endforeach; ?>
</ul>
