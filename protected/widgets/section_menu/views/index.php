<?php

?>
<div class="sectionMenu">
    <table>
        <tr>
            <?php foreach ($menuItems as $menuItem): ?>
                <?php
                    $itemOptions = '';
                    if (!empty($menuItem['itemOptions'])) {
                        foreach ($menuItem['itemOptions'] as $key => $val) {
                            $itemOptions .= $key.' = "'.CHtml::encode($val).'" ';
                        }
                    }
                ?>
                <td>
                    <a href="<?= CHtml::encode($menuItem['url']) ?>" title="<?= CHtml::encode($menuItem['label']) ?>" <?= $itemOptions ?>>
                        <img class="sectionMenuIcon" src="<?= CHtml::encode($imagesFolderUrl.DIRECTORY_SEPARATOR.$iconTypes[$menuItem['iconType']]) ?>" />
                    </a>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
</div>
