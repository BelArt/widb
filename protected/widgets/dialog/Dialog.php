<?php
/**
 * Виджет для генерации диалогового окна
 */
class Dialog extends CWidget
{
    public $messageOk;
    public $messageCancel;
    public $messageClose;
    public $messageError;

    public function init()
    {
        if (empty($this->messageOk)) {
            $this->messageOk = Yii::t('common', 'Ок');
        }

        if (empty($this->messageCancel)) {
            $this->messageCancel = Yii::t('common', 'Отмена');
        }

        if (empty($this->messageClose)) {
            $this->messageClose = Yii::t('common', 'Закрыть');
        }

        if (empty($this->messageError)) {
            $this->messageError = Yii::t('common', 'Ошибка');
        }
    }

    public function run()
    {
        $jsFile = Yii::app()->assetManager->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'dialog.js');
        Yii::app()->clientScript->registerScriptFile($jsFile);

        $this->render('index', array(
            'messageOk' => $this->messageOk,
            'messageCancel' => $this->messageCancel,
            'messageClose' => $this->messageClose,
            'messageError' => $this->messageError
        ));
    }
}