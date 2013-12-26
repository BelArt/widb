$(function(){

    // Сброс формы
    $('._collectionForm_resetButton').click(function(event){

        event.preventDefault();

        var $form = $(this).parents('._collectionForm_form');

        // сбрасываем форму
        $form.get(0).reset();

        // сбрасываем ошибки
        $form.children('._collectionForm_hideErrorsKeypress').trigger('keypress');
        $form.children('._collectionForm_hideErrorsChange').trigger('change');
    });

    // Сбрасывает ошибки поля в случае ввода данных в поле
    $('._collectionForm_hideErrorsKeypress').keypress(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.next('span.error').remove();
        $Obj.parent().prev('label.error').removeClass('error');
    });

    // Сбрасывает ошибки поля в случае onchange
    $('._collectionForm_hideErrorsChange').change(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.next('span.error').remove();
        $Obj.parent().prev('label.error').removeClass('error');
    });

    // при отправке формы - сбрасываем обработчик onbeforeunload, см. upload-files.js
    $('._collectionForm_form').submit(function(){
        window.onbeforeunload = null;
    });

    // удаление превью
    $('._collectionForm_deletePreviewButton').click(function(){

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
                $('._collectionForm_previewBlock').remove();
                $('._collectionForm_xuploadBlock').show();

            },
            error: function() {
                // @todo тут надо что-то сделать
            }
        });
    });

});
