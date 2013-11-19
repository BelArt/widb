<?php
/* @var $this ObjectsController */
/* @var $data Objects */

$itemUrl = $this->createUrl('objects/view', array('id' => $data->id));
?>

<li class="itemThumbnailBig">
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
    </a>
    <table class="itemThumbnailDescriptionBlock">
        <tr>
            <td class="itemThumbnailTextBlock">
                <p class='itemThumbnailName'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
                <?php if(!empty($data->author->initials)): ?>
                    <p class='itemThumbnailAuthor'><?= CHtml::encode($data->author->initials) ?></p>
                <?php endif; ?>
            </td>
            <td class="itemThumbnailCheckboxBlock"><input type="checkbox" /></td>
        </tr>
    </table>
</li>