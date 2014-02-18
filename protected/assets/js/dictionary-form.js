$(function(){

    // Сброс формы
    $('._dictionaryForm_resetButton').click(function(event){

        event.preventDefault();

        var $form = $(this).parents('._dictionaryForm_form');

        // сбрасываем форму
        $form.get(0).reset();

        // сбрасываем ошибки
        $form.children('._dictionaryForm_hideErrorsKeypress').trigger('keypress');
        $form.children('._dictionaryForm_hideErrorsChange').trigger('change');
    });

    // Сбрасывает ошибки поля в случае ввода данных в поле
    $('._dictionaryForm_hideErrorsKeypress').keypress(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // Сбрасывает ошибки поля в случае onchange
    $('._dictionaryForm_hideErrorsChange').change(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

});
