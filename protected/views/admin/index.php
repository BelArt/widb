<?php foreach($actions as $action): ?>
    <?php echo CHtml::link(CHtml::encode($action['label']), $action['url']); ?>
<?php endforeach; ?>