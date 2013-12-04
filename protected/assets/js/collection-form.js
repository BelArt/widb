$(function(){

    // Отметка,что коллекция временная
    /*$('._collectionForm_tempCollectionCheckbox').change(function(){
        // временная
        if ($(this).is(':checked')) {

            // показываем поле Открытая временная коллекция
            $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled':false});
            $('._collectionForm_tempCollectionPublicBlock').show('fast');

            // скрываем поле Родительская коллекция
            $('._collectionForm_parentCollectionBlock').hide('fast');
            $('._collectionForm_parentCollectionSelect').attr({'disabled': true});

        } else { // обычная

            // скрываем поле Открытая временная коллекция
            $('._collectionForm_tempCollectionPublicBlock').hide('fast');
            $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled': true});

            // показываем поле Родительская коллекция
            $('._collectionForm_parentCollectionSelect').attr({'disabled':false});
            $('._collectionForm_parentCollectionBlock').show('fast');
        }
    });*/

    // Сброс формы
    $('._collectionForm_resetButton').click(function(event){

        event.preventDefault();

        // скрываем поле Открытая временная коллекция
        /*$('._collectionForm_tempCollectionPublicBlock').hide('fast');
        $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled': true, 'checked':true});

        // показываем поле Родительская коллекция
        $('._collectionForm_parentCollectionSelect').attr({'disabled':false});
        $('._collectionForm_parentCollectionBlock').show('fast');*/

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

});
