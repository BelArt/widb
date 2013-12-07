$(function(){

    // Сброс формы
    $('._collectionForm_resetButton').click(function(event){

        event.preventDefault();

        // сбрасываем форму
        $(this).closest('form').get(0).reset();

        // сбрасываем ошибки
        $('._collectionForm_hideErrorsKeypress').trigger('keypress');
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

});
