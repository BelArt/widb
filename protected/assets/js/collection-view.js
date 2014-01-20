$(function(){

    $('._deleteSelectedObjects').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {

                // определяем, какие объекты удалять - собираем массив с их айдишниками
                var idsOfObjectsToDelete = [];
                $('._objectItem:checked').each(function(){
                    idsOfObjectsToDelete.push($(this).data('object-id'));
                });

                closeDialog();

                $.ajax({
                    url: '/site/ajax',
                    type: 'POST',
                    data: {
                        action: 'deleteObjects',
                        params: {
                            ids: idsOfObjectsToDelete
                        }
                    },
                    success: function() {
                        window.location.reload(true);
                    },
                    error: function(jqXHR) {

                        showDialog(
                            $('._dialog').data('message-error'),
                            jqXHR.responseText,
                            function() {
                                closeDialog();
                            }
                        );

                    }
                });


            },
            function() {
                closeDialog();
            }
        );

        return false;
    });

});
