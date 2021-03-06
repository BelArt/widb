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
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // Сбрасывает ошибки поля в случае onchange
    $('._collectionForm_hideErrorsChange').change(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // при отправке формы - сбрасываем обработчик onbeforeunload, см. upload-files.js
    $('._collectionForm_form').submit(function(){
        window.onbeforeunload = null;
    });

});
