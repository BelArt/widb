/**
 * Скрывает ошибки поля onkeypress
 * @param $Obj JQuery-объект поля
 */
function hideErrors($Obj) {
    $Obj.removeClass('error');
    $Obj.next('span.error').remove();
    $Obj.prev('label.error').removeClass('error');
}
