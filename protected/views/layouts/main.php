<?php
/* @var $this Controller
 * @todo форму поиска сделать виджетом
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

    <? Yii::app()->clientScript->registerPackage('defaultLayout'); ?>
    <? Yii::app()->clientScript->registerPackage('layoutFix'); ?>
    <? Yii::app()->clientScript->registerPackage('global'); ?>

	<title>
        <?php
            $this->widget('application.widgets.PageTitle',array(
                'pageTitle' => $this->pageTitle
            ));
        ?>
    </title>
</head>

<body>

<div class="container" id="page">

	<div id="mainmenu">
        <?php
            $this->widget(
                'bootstrap.widgets.TbNavbar',
                array(
                    'brand' => Yii::app()->name,
                    'fixed' => false,
                    'items' => array(
                        $this->widget('bootstrap.widgets.TbButton',
                            array(
                                'label' => 'Выйти',
                                'type' => 'primary',
                                'size' => 'small',
                                'url' => Yii::app()->urlManager->createUrl('site/logout'),
                                'htmlOptions' => array(
                                    'class' => 'navbarBtnLogout pull-right navbarItem '
                                )
                            ),true
                        ),
                        '<p class="pull-right navbarItem navbarUserInfo">Вы зашли как <strong>'.Yii::app()->user->name.'</strong></p>',

                        '<!--
<form class="navbar-form pull-right navbarItem" action="#">
                            <div class="input-prepend">
                                <span class="add-on">
                                    <i class="icon-search"></i>
                                </span>
                                <input class="input-large" placeholder="Поиск" name="" id="" type="text">
                            </div>
                            <select name="" id="" class="navbarSelect">
                                <option value="0">Выберите поле</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <button type="submit" class="btn navbarBtnSubmit">Искать</button>
                        </form>-->
',

                    )
                )
            );
        ?>
	</div><!-- mainmenu -->

    <div class="breadcrumbsAndSectionMenu">
        <div class="sectionMenuWrapper">
            <?php
            $this->widget('application.widgets.section_menu.SectionMenu', array(
                'menuItems' => $this->pageMenu,
            ));
            ?>
        </div>
        <div class="breadcrumbsWrapper">
            <?php
            $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                'links' => $this->breadcrumbs,
            ));
            ?>
        </div>
        <div class="clear"></div>
    </div>

    <?php
        $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => false,
            'fade' => true,
            'closeText' => '&times;',
            'events' => array(),
            'htmlOptions' => array(),
            'userComponentId' => 'user',
            'alerts' => array(
                'success',
                'info',
                'warning',
                'error'
            ),
        ));
    ?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<a href="http://eposgroup.ru">ЗАО "Группа ЭПОС"</a> &copy; <?php echo date('Y'); ?>
	</div><!-- footer -->

    <!-- Диалог -->
    <?php
        $this->widget('application.widgets.dialog.Dialog');
    ?>
    <!-- / Диалог -->

</div><!-- page -->

</body>
</html>
