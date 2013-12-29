$(function() {

    // удаляем подгруженные несохраненные файлы пользователя, если уходим со страницы
    window.onbeforeunload = function() {
        $.ajax({
            url: '/site/ajax',
            type: 'POST',
            async: false,
            data: {
                action: 'clearUserUploads'
            }
        });
    }

    // удаление превью
    $('._uploadFiles_deletePreviewButton').click(function(){

        var type = $(this).data('type');
        var id = $(this).data('id');

        $.ajax({
            url: '/site/ajax',
            type: 'POST',
            data: {
                action: 'deletePreview',
                params: {
                    type: type,
                    id: id
                }
            },
            success: function() {
                // при успешном удалении показываем блок загрузки превью
                $('._uploadFiles_previewBlock').remove();
                $('._uploadFiles_xuploadBlock').show();

                // и снимаем галочку Есть превью
                $('._hasPreviewCheckbox').removeAttr('checked');

            },
            error: function() {
                // @todo тут надо что-то сделать
            }
        });
    });

});