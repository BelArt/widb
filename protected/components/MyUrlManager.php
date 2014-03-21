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
     * @var array дефолтные значения GET-параметров для действия просмотра обычной коллекции. Все они появятся в
     * соответствующем урле
     */
    private $normalCollectionUrlDefaultParams = array(
        'ov' => 'th',
        'cv' => 'th',
        'tb' => 'ob',
        'opp' => CPagination::DEFAULT_PAGE_SIZE
    );

    /**
     * @var array дефолтные значения GET-параметров для действия просмотра временной коллекции. Все они появятся в
     * соответствующем урле
     */
    private $tempCollectionUrlDefaultParams = array(
        'ov' => 'th',
        'opp' => CPagination::DEFAULT_PAGE_SIZE
    );

    /**
     * @var array дефолтные значения GET-параметров для действия просмотра объекта. Все они появятся в
     * соответствующем урле
     */
    private $objectUrlDefaultParams = array(
        'iv' => 'th',
    );

    /**
     * @var array дефолтные значения GET-параметров для действия просмотра всех коллекций. Все они появятся в
     * соответствующем урле
     */
    private $collectionsUrlDefaultParams = array(
        'cv' => 'th',
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
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->createUrl('collections/view', array_merge(
            array('id' => $Collection->id),
            $this->getFinalUrlParams($params, 'normalCollection')
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
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->createUrl('collections/viewTemp', array_merge(
            array('id' => $Collection->id),
            $this->getFinalUrlParams($params, 'tempCollection')
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
            throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        return $this->createUrl('objects/view', array_merge(
            array('id' => $Object->id),
            $this->getFinalUrlParams($params, 'objects')
        ));
    }

    /**
     * Создает урл "с сохраненными настройками отображения" на страницу просмотра всех коллекций.
     * @param array $params GET-параметры, которыми надо перезаписать дефолтные параметры
     * @return string урл "с сохраненными настройками отображения" на страницу объекта
     * @throws CException
     */
    public function createCollectionsUrl(array $params = array())
    {
        return $this->createUrl('collections/index', $this->getFinalUrlParams($params, 'collections'));
    }

    private function getFinalUrlParams(array $params, $type)
    {
        $result = array();

        $defaultParamsArray = array();
        switch ($type) {
            case 'normalCollection':
                $defaultParamsArray = $this->normalCollectionUrlDefaultParams;
                break;
            case 'tempCollection':
                $defaultParamsArray = $this->tempCollectionUrlDefaultParams;
                break;
            case 'objects':
                $defaultParamsArray = $this->objectUrlDefaultParams;
                break;
            case 'collections':
                $defaultParamsArray = $this->collectionsUrlDefaultParams;
                break;
            default:
                throw new CException(Yii::t('common', 'Произошла ошибка!'));
        }

        foreach ($defaultParamsArray as $paramName => $paramDefaultValue) {

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

    /**
     * Возвращает дефолтные GET-параметры для страницы просмотра всех коллекций.
     * @return array дефолтные GET-параметры для страницы просмотра всех коллекций
     */
    public function getCollectionsUrlDefaultParams()
    {
        return $this->collectionsUrlDefaultParams;
    }
} 