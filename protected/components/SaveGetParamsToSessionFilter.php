<?php

/**
 * Фильтр сохраняет GET-параметры в state.
 *
 * Фильтр выполняется после действий view и viewTemp для коллекций и объектов, и сохраняет GET-параметры в state. Это
 * нужно для того, чтобы генерить ссылки с сохраненными настройками отображения дочерних коллекций/объектов/изображений,
 * для создания эффекта "запоминания настроек отображения".
 */
class SaveGetParamsToSessionFilter extends CFilter
{
    protected function preFilter($filterChain)
    {
        foreach ($this->getActionDefaultGetParams() as $paramName => $paramDefaultValue) {
            $paramValue = Yii::app()->request->getQuery($paramName);
            if (!empty($paramValue)) {
                //Yii::app()->session[$paramName] = $paramValue;
                Yii::app()->user->setState(Yii::app()->urlManager->getKeyPrefixForDisplaySettings().$paramName, $paramValue);
            }
        }

        $filterChain->run();
    }

    /**
     * Возвращает массив дефолтных GET-параметров для соответствующего экшена
     * @return array массив дефолтных GET-параметров для соответствующего экшена
     */
    private function getActionDefaultGetParams()
    {
        $actionDefaultGetParams = array();

        if (Yii::app()->controller->id == 'collections' && Yii::app()->controller->action->id == 'view') {
            $actionDefaultGetParams = Yii::app()->urlManager->getNormalCollectionUrlDefaultParams();
        } elseif (Yii::app()->controller->id == 'collections' && Yii::app()->controller->action->id == 'viewTemp') {
            $actionDefaultGetParams = Yii::app()->urlManager->getTempCollectionUrlDefaultParams();
        } elseif (Yii::app()->controller->id == 'objects' && Yii::app()->controller->action->id == 'view') {
            $actionDefaultGetParams = Yii::app()->urlManager->getObjectUrlDefaultParams();
        }

        return $actionDefaultGetParams;
    }
} 