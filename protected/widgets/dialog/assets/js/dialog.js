/**
 * Показывает диаолговой окно. Используется jquery ui плагин dialog
 * @param title заголовок
 * @param msg сообщение
 * @param okButtonCallback колбэк на нажатие кнопки ОК
 * @param cancelButtonCallback колбэк на нажатие кнопки Отмена
 */
function showDialog(title, msg, okButtonCallback, cancelButtonCallback) {

    var $dialog = $('._dialog');

    title = title || '';
    msg = msg || '';
    okButtonCallback = okButtonCallback || null;
    cancelButtonCallback = cancelButtonCallback || null;

    var buttons = {};
    if (okButtonCallback) {
        buttons[$dialog.data('button-ok')] = okButtonCallback;
    }
    if (cancelButtonCallback) {
        buttons[$dialog.data('button-cancel')] = cancelButtonCallback;
    }

    //msg = $("<div/>").html(msg).text();
    $dialog.html(msg);

    $dialog.dialog({
        title: title,
        modal: true,
        resizable: false,
        buttons: buttons
    });
}

/**
 * Закрывает диалоговое окно
 * @see showDialog()
 */
function closeDialog() {

    $('._dialog').dialog('close');
}

/**
 * Полуичть тайтл для сообщения об ошибке
 * @returns {*|jQuery}
 */
function getDialogErrorMessageTitle() {

    return $('._dialog').data('message-error');
}
