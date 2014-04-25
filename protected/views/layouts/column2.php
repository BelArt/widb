<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-8 first">

    <?php /*if (!empty($this->pageMenu)): */?><!--
        <div id="page-menu">
            <?php
/*                $this->widget(
                    'bootstrap.widgets.TbMenu',
                    array(
                        'type' => 'pills',
                        'stacked' => true,
                        'items' => $this->pageMenu,
                    )
                );
            */?>
        </div>
    --><?php /*endif; */?>

    <div id="sidebar" class="hidden-print">
        <?php
            $this->widget('CTreeView', array(
                'data' => Collections::getTreeNormal(),
                'htmlOptions' => array(
                    'class' => 'filetree'
                )
            ));
        ?>
    </div><!-- sidebar -->

    <?php
        $treeTemp = Collections::getTreeTemp();
    ?>
    <?php if ($treeTemp): ?>
        <div class="sidebar2 hidden-print">
            <?php
            $this->widget('CTreeView', array(
                'data' => $treeTemp,
                'htmlOptions' => array(
                    'class' => 'filetree'
                )
            ));
            ?>
        </div>
    <?php endif; ?>

    <?php if ($this->getAdminMenu()): ?>
        <div id="admin-menu" class="hidden-print">
            <?php
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'stacked' => true,
                'items' => $this->getAdminMenu(),
            ));
            ?>
        </div>
    <?php endif; ?>

</div>
<div class="span-21 last">
	<div id="content">
        <?php if(!empty($this->pageName)): ?>
            <h1 class="pageName"><?php echo CHtml::encode($this->pageName) ?></h1>
        <?php endif; ?>
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>