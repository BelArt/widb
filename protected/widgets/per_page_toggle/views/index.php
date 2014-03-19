<?php
/* @var $text string */
/* @var $valueSet array */
/* @var $varName string */
?>
<table>
    <tr>
        <td class="perPageToggleTextContainer"><?= CHtml::encode($text) ?></td>
        <td class="perPageToggleSelectContainer">
            <select size="1" data-var-name="<?= CHtml::encode($varName) ?>" class="perPageToggleSelect _perPageToggleSelect">
                <optgroup>
                    <?php foreach ($valueSet as $val): ?>
                        <option value="<?= CHtml::encode($val) ?>" <?= $val == $perPageValue ? 'selected' : '' ?>><?= CHtml::encode($val) ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </td>
    </tr>
</table>