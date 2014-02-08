<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $collectionsToMoveTo array */
?>

<select size="1" class="_collectionToMoveToSelect" data-object-id="<?= CHtml::encode($Object->id) ?>">
    <?php foreach ($collectionsToMoveTo as $Collection) { ?>
        <option value="<?= CHtml::encode($Collection->id) ?>"><?= CHtml::encode($Collection->name) ?></option>
    <?php } ?>
</select>