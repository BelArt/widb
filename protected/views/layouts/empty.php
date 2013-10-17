<?php
/* @var $this Controller */
$pageTitle = !empty($this->pageTitle) ? $this->pageTitle : '';
$pageName = !empty($this->pageName) ? $this->pageName : '';
$breadcrumbs = !empty($this->breadcrumbs) ? $this->breadcrumbs : array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

    <? Yii::app()->clientScript->registerPackage('defaultLayout'); ?>
    <? Yii::app()->clientScript->registerPackage('emptyLayout'); ?>

	<title><?php echo CHtml::encode($pageTitle); ?></title>
</head>

<body>

    <table class='emptyLayout'>
        <tr>
            <td class='emptyLayoutContainer'>

                <h1 class='title'><?php echo CHtml::encode($pageName); ?></h1>
                <?php echo $content; ?>

            </td>
        </tr>
    </table>

</body>
</html>
