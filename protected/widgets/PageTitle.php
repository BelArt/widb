<?php
/**
 * Class PageTitle
 */

class PageTitle extends CWidget
{
    public $pageTitle;

    public function run()
    {
        if (!is_array($this->pageTitle)) {
            throw new CException(Yii::t('common', 'В качестве pageTitle передан не массив!'));
        }

        echo CHtml::encode(join(' | ', $this->pageTitle));
    }
}