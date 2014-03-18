<?php

?>
<table class="childrenMenu">
    <tr>
        <td class="childrenMenuName" ><?= CHtml::encode($menuName) ?></td>
        <?php foreach ($menuItems as $menuItem): ?>
            <?php
                $itemOptions = '';
                if (!empty($menuItem['itemOptions'])) {
                    foreach ($menuItem['itemOptions'] as $key => $val) {
                        $itemOptions .= $key.'="'.CHtml::encode($val).'" ';
                    }
                }

                $tdOptions = '';
                if (!empty($menuItem['tdOptions'])) {
                    foreach ($menuItem['tdOptions'] as $key => $val) {
                        $tdOptions .= $key.'="'.CHtml::encode($val).'" ';
                    }
                }
            ?>
            <td <?= $tdOptions ?>>
                <a href="<?= CHtml::encode($menuItem['url']) ?>" title="<?= CHtml::encode($menuItem['label']) ?>" <?= $itemOptions ?>>
                    <img class="childrenMenuItemIcon" src="<?= CHtml::encode($imagesFolderUrl.DIRECTORY_SEPARATOR.$iconTypes[$menuItem['iconType']]) ?>" />
                </a>
            </td>
        <?php endforeach; ?>
    </tr>
</table>

