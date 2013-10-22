<?php
/* @var $this Controller */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

    <? Yii::app()->clientScript->registerPackage('defaultLayout'); ?>
    <? Yii::app()->clientScript->registerPackage('boosterFix'); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<!--<div id="header">
		<div id="logo"><?php echo CHtml::encode($this->pageName); ?></div>
	</div>--><!-- header -->

	<div id="mainmenu">
		<?php /*$this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); */?>
        <?php
            $this->widget(
                'bootstrap.widgets.TbNavbar',
                array(
                    'brand' => Yii::app()->name,
                    'fixed' => false,
                    'items' => array(
                        '<form class="navbar-search pull-left">
                                <input type="text" class="search-query" placeholder="искать">
                            </form>'
                    )
                )
            );
        ?>
	</div><!-- mainmenu -->

	<?php if(!empty($this->breadcrumbs)):?>
        <?php
            $this->widget(
                'bootstrap.widgets.TbBreadcrumbs',
                array(
                    'links' => $this->breadcrumbs,
                )
            );
        ?>
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<a href="http://eposgroup.ru">ЗАО "Группа ЭПОС"</a> &copy; <?php echo date('Y'); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
