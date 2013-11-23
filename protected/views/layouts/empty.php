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
    <? Yii::app()->clientScript->registerPackage('global'); ?>
    <? Yii::app()->clientScript->registerPackage('emptyLayout'); ?>

	<title>
        <?php
            $this->widget('application.widgets.PageTitle',array(
                'pageTitle' => $this->pageTitle
            ));
        ?>
    </title>
</head>

<body>

    <table class='emptyLayout'>
        <tr>
            <td class='emptyLayoutContainer'>

                <h1 class='title'><?php echo CHtml::encode($this->pageName); ?></h1>
                <?php echo $content; ?>

            </td>
        </tr>
    </table>

</body>
</html>
