<?php

class MyAccessControlFilter extends CAccessControlFilter
{
    public function init()
    {
        $this->message = Yii::t('common', 'Запрашиваемая Вами страница недоступна!');
        parent::init();
    }

} 