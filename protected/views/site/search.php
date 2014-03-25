<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $resultsDataProvider,
    'itemView' => '_viewSearchItemList',
    'template' => '{summary}'.PHP_EOL.'{items}'.PHP_EOL.'{pager}',
    'ajaxUpdate' => false,
    'emptyText' => ''
));
?>