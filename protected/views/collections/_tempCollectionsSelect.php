<?php
/* @var $this CollectionsController */
/* @var $tempCollections array */
?>

<select size="1" class="_tempCollectionSelect">
    <?php foreach ($tempCollections as $Collection) { ?>
        <option value="<?= CHtml::encode($Collection->id) ?>"><?= CHtml::encode($Collection->name) ?></option>
    <?php } ?>
</select>