<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-7">
    <div id="sidebar">
        <?php
            $this->widget('CTreeView', array(
                'data' => Collections::getStructureForTreeViewWidget()
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