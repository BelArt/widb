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
});