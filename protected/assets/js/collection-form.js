$(function(){

    // при отметке, что коллекция временная, показываем поле Публичная временная коллекция
    $('._collectionForm_tempCollectionCheckbox').change(function(){
        if ($(this).is(':checked')) {
            $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled':false});
            $('._collectionForm_tempCollectionPublicBlock').show('fast');
        } else {
            $('._collectionForm_tempCollectionPublicBlock').hide('fast');
            $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled': true, 'checked':true});
        }
    });

    // при сбросе формы скрываем поле Открытая временная коллекция
    $('._collectionForm_resetButton').click(function(event){

        event.preventDefault();

        $('._collectionForm_tempCollectionPublicBlock').hide('fast');
        $('._collectionForm_tempCollectionPublicCheckbox').attr({'disabled': true, 'checked':true});

        $(this).closest('form').get(0).reset();

    });

});
