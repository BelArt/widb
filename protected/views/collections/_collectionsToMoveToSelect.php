<?php
/* @var $this CollectionsController */
/* @var $collectionsToMoveTo array */
?>

<select size="1" class="_collectionToMoveToSelect">
    <?php foreach ($collectionsToMoveTo as $Collection) { ?>
        <option value="<?= CHtml::encode($Collection->id) ?>"><?= CHtml::encode($Collection->name) ?></option>
    <?php } ?>
</select>