$(function(){

    // Сброс формы
    $('._userForm_resetButton').click(function(event){

        event.preventDefault();

        var $form = $(this).parents('._userForm_form');

        // сбрасываем форму
        $form.get(0).reset();

        // сбрасываем ошибки
        $form.children('._userForm_hideErrorsKeypress').trigger('keypress');
        $form.children('._userForm_hideErrorsChange').trigger('change');
    });

    // Сбрасывает ошибки поля в случае ввода данных в поле
    $('._userForm_hideErrorsKeypress').keypress(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

    // Сбрасывает ошибки поля в случае onchange
    $('._userForm_hideErrorsChange').change(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.parents('.controls').find('span.error').remove();
        $Obj.parents('.controls').prev('label.error').removeClass('error');
    });

});
