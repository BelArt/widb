<?php

/**
 * Расширение стандартного УрлМенеджера.
 *
 * Сделано изначально для создания методов генерации ссылок на сущности с сохраненными настройками отображения, для
 * создания эффекта "сохранения настроек отображения".
 */
class MyUrlManager extends CUrlManager
{
    /**
     * @var array дефолтные значения GET-параметров для действия просмотра обычной коллекции
     */
    private $normalCollectionUrlDefaultParams = array(
        'ov' => 'th',
        'cv' => 'th',
        'tb' => 'ob',
        'opp' => CPagination::DEFAULT_PAGE_SIZE
    );

    /**
     * @var array дефолтные значения GET-параметров для действия просмотра временной коллекции
     */
    private $tempCollectionUrlDefaultParams = array(
        'ov' => 'th',
        'opp' => CPagination::DEFAULT_PAGE_SIZE
    );

    /**
     * @var array дефолтные значения GET-параметров для действия просмотра объекта
     */
    private $objectUrlDefaultParams = array(
        'iv' => 'th',
    );

    /**
     * Создает урл "с сохраненными настройками отображения" на страницу обычной коллекции.
     * @param Collections $Collection модель обычной коллекции
     * @param array $params GET-параметры, которыми надо перезаписать дефолтные параметры
     * @return string урл "с сохраненными настройками отображения" на страницу обычной коллекции
     * @throws CException
     */
    public function createNormalCollectionUrl(Collections $Collection, array $params = array())
    {
        if ($Collection->temporary || $Collection->isNewRecord) {
            throw new CException();
        }

        return $this->createUrl('collections/view', array_merge(
            array('id' => $Collection->id),
            $this->getNormalCollectionUrlParams($params)
        ));
    }

    /**
     * Создает урл "с сохраненными настройками отображения" на страницу временной коллекции.
     * @param Collections $Collection модель временной коллекции
     * @param array $params GET-параметры, которыми надо перезаписать дефолтные параметры
     * @return string урл "с сохраненными настройками отображения" на страницу временной коллекции
     * @throws CException
     */
    public function createTempCollectionUrl(Collections $Collection, array $params = array())
    {
        if (!$Collection->temporary || $Collection->isNewRecord) {
            throw new CException();
        }

        return $this->createUrl('collections/viewTemp', array_merge(
            array('id' => $Collection->id),
            $this->getTempCollectionUrlParams($params)
        ));
    }

    /**
     * Создает урл "с сохраненными настройками отображения" на страницу объекта.
     * @param Objects $Object модель объекта
     * @param array $params GET-параметры, которыми надо перезаписать дефолтные параметры
     * @return string урл "с сохраненными настройками отображения" на страницу объекта
     * @throws CException
     */
    public function createObjectUrl(Objects $Object, array $params = array())
    {
        if ($Object->isNewRecord) {
            throw new CException();
        }

        return $this->createUrl('objects/view', array_merge(
            array('id' => $Object->id),
            $this->getObjectUrlParams($params)
        ));
    }

    private function getNormalCollectionUrlParams(array $params)
    {
        $result = array();

        foreach ($this->normalCollectionUrlDefaultParams as $paramName => $paramDefaultValue) {

            $paramValue = null;

            if (!empty($params[$paramName])) {
                $paramValue = $params[$paramName];
            } elseif (Yii::app()->user->hasState($this->getKeyPrefixForDisplaySettings().$paramName)) {
                $paramValue = Yii::app()->user->getState($this->getKeyPrefixForDisplaySettings().$paramName);
            } else {
                $paramValue = $paramDefaultValue;
            }

            $result[$paramName] = $paramValue;
        }

        return $result;
    }

    private function getTempCollectionUrlParams(array $params)
    {
        $result = array();

        foreach ($this->tempCollectionUrlDefaultParams as $paramName => $paramDefaultValue) {

            $paramValue = null;

            if (!empty($params[$paramName])) {
                $paramValue = $params[$paramName];
            } elseif (Yii::app()->user->hasState($this->getKeyPrefixForDisplaySettings().$paramName)) {
                $paramValue = Yii::app()->user->getState($this->getKeyPrefixForDisplaySettings().$paramName);
            } else {
                $paramValue = $paramDefaultValue;
            }

            $result[$paramName] = $paramValue;
        }

        return $result;
    }

    private function getObjectUrlParams(array $params)
    {
        $result = array();

        foreach ($this->objectUrlDefaultParams as $paramName => $paramDefaultValue) {

            $paramValue = null;

            if (!empty($params[$paramName])) {
                $paramValue = $params[$paramName];
            } elseif (Yii::app()->user->hasState($this->getKeyPrefixForDisplaySettings().$paramName)) {
                $paramValue = Yii::app()->user->getState($this->getKeyPrefixForDisplaySettings().$paramName);
            } else {
                $paramValue = $paramDefaultValue;
            }

            $result[$paramName] = $paramValue;
        }

        return $result;
    }

    /**
     * Возвращает дефолтные GET-параметры для страницы обычной коллекции.
     * @return array дефолтные GET-параметры для страницы обычной коллекции
     */
    public function getNormalCollectionUrlDefaultParams()
    {
        return $this->normalCollectionUrlDefaultParams;
    }

    /**
     * Возвращает дефолтные GET-параметры для страницы временной коллекции.
     * @return array дефолтные GET-параметры для страницы временной коллекции
     */
    public function getTempCollectionUrlDefaultParams()
    {
        return $this->tempCollectionUrlDefaultParams;
    }

    /**
     * Возвращает дефолтные GET-параметры для страницы объекта.
     * @return array дефолтные GET-параметры для страницы объекта
     */
    public function getObjectUrlDefaultParams()
    {
        return $this->objectUrlDefaultParams;
    }

    /** Возвращает префикс для хранения данных об отображенияя в state.
     * @return string префикс для хранения данных об отображенияя в state
     */
    public function getKeyPrefixForDisplaySettings()
    {
        return '_displaySettings_';
    }
} 