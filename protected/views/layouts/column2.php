<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-8 first">
    <div id="sidebar">
        <?php
            $this->widget('CTreeView', array(
                'data' => Collections::getStructureForTreeViewWidget(),
                'htmlOptions' => array(
                    'class' => 'filetree'
                )
            ));
        ?>
    </div><!-- sidebar -->
</div>
<div class="span-21 last">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>