<ul class="list">
    <?php foreach ($services as $service): ?>
        <li>
            <?php echo CHtml::link($service, array('functions', 'serviceName' => $service)); ?>
        </li>
    <?php endforeach; ?>
</ul>




