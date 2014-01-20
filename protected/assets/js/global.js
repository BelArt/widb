function showDialog(title, msg, params) {

    title = title || '';
    msg = msg || '';

    var $dialog = $('#_dialog');
    $dialog.attr('title', title);
    $dialog.html(msg);

    $dialog.dialog({
        modal: true,
        buttons: params.buttons
    });
}
