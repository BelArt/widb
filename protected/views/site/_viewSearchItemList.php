<?php

switch (get_class($data)) {
    case 'Collections':
        $this->renderPartial('application.views.collections._viewListNoCheckbox', array('data' => $data));
        break;
    case 'Objects':
        $this->renderPartial('application.views.objects._viewListNoCheckbox', array('data' => $data));
        break;
    case 'Images':
        $this->renderPartial('application.views.images._viewListNoCheckbox', array('data' => $data));
        break;
}