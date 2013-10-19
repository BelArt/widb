$(function(){
    /**
     * Скрывает ошибки поля при вводе данных
     */
    $('._hideErrors').keypress(function(){
        $Obj = $(this);
        $Obj.removeClass('error');
        $Obj.next('span.error').remove();
        $Obj.prev('label.error').removeClass('error');
    });
});

