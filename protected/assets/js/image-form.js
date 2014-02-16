$(function(){

    // Сброс формы
    $('._imageForm_resetButton').click(function(event){

        event.preventDefault();

        var $form = $(this).parents('._imageForm_form');

        // сбрасываем форму
        $form.get(0).reset();

        // сбрасываем ошибки
        $form.children('._imageForm_hideErrorsKeypress').trigger('keypress');
        $form.children('._imageForm_hideErrorsChange').trigger('change');
    });

    // Сбрасывает ошибки поля в случае ввода данных в поле
    $('._imageForm_hideErrorsKeypress').keypress(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // Сбрасывает ошибки поля в случае onchange
    $('._imageForm_hideErrorsChange').change(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // при отправке формы - сбрасываем обработчик onbeforeunload, см. upload-files.js
    $('._imageForm_form').submit(function(){
        window.onbeforeunload = null;
    });

});
