$(function(){

    /**
     * Хак - для каждой строки таблицы прописываем айдишник объекта в атрибут ячейки колонки с чекбоксом.
     * Нужно для функционала удаления выбранных объектов
     */
    $('._objectLinkInTableRow').each(function(){

        var link = $(this).attr('href');
        var objectId = link.substring(link.lastIndexOf('/')+1);

        $(this).parent('td').prev('td').children('._objectItem').attr('data-object-id', objectId);

    });

    /**
     * Удаление выбранных объектов из обычной коллекции
     */
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

    /**
     * Удаление выбранных объектов из временной коллекции
     */
    $('._deleteSelectedObjectsFromTempCollection').click(function(){

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
                        action: 'deleteObjectsFromTempCollection',
                        params: {
                            ids: idsOfObjectsToDelete,
                            collectionId: $this.data('temp-collection-id')
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
