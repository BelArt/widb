<form class="navbar-form pull-right searchForm" action="<?= CHtml::encode($action) ?>" method="get">
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-search"></i>
        </span>
        <input class="input-large" placeholder="<?= CHtml::encode(Yii::t('common', 'Поиск')) ?>" name="search" id="" type="text" value="<?= CHtml::encode(Yii::app()->request->getQuery('search', '')) ?>">
    </div>
    <span class="searchFormCategoryText"><?= CHtml::encode(Yii::t('common', 'Искать')) ?></span>
    <select name="category" id="" class="searchFormSelect">
        <?php foreach($selectValues as $key => $val): ?>
            <option <?= Yii::app()->request->getQuery('category') == $key ? 'selected' : '' ?> value="<?= CHtml::encode($key) ?>"><?= CHtml::encode($val) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn searchFormBtnSubmit">Искать</button>
</form>