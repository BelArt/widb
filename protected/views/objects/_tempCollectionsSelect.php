<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $tempCollections array */
?>

<select size="1" class="_tempCollectionSelect" data-object-id="<?= CHtml::encode($Object->id) ?>">
    <?php foreach ($tempCollections as $Collection) { ?>
        <option value="<?= CHtml::encode($Collection->id) ?>"><?= CHtml::encode($Collection->name) ?></option>
    <?php } ?>
</select>