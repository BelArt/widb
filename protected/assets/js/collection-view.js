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
     * Хак - для каждой строки таблицы прописываем айдишник дочерней коллекции в атрибут ячейки колонки с чекбоксом.
     * Нужно для функционала удаления выбранных дочерних коллекций
     */
    $('._collectionLinkInTableRow').each(function(){

        var link = $(this).attr('href');
        var collectionId = link.substring(link.lastIndexOf('/')+1);

        $(this).parent('td').prev('td').children('._collectionItem').attr('data-collection-id', collectionId);

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
                            getDialogErrorMessageTitle(),
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
                            getDialogErrorMessageTitle(),
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
     * Удаление коллекции
     */
    $('._deleteCollection').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {
                window.location = $this.children('a').attr('href');
            },
            function() {
                closeDialog();
            }
        );

        return false;
    });

    /**
     * Удаление временной коллекции
     */
    $('._deleteTempCollection').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {
                window.location = $this.children('a').attr('href');
            },
            function() {
                closeDialog();
            }
        );

        return false;
    });

    /**
     * Удаление выбранных дочерних коллекций
     */
    $('._deleteSelectedChildCollections').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {

                // определяем, какие объекты удалять - собираем массив с их айдишниками
                var idsOfCollectionsToDelete = [];
                $('._collectionItem:checked').each(function(){
                    idsOfCollectionsToDelete.push($(this).data('collection-id'));
                });

                closeDialog();

                $.ajax({
                    url: '/site/ajax',
                    type: 'POST',
                    data: {
                        action: 'deleteChildCollections',
                        params: {
                            ids: idsOfCollectionsToDelete
                        }
                    },
                    success: function() {
                        window.location.reload(true);
                    },
                    error: function(jqXHR) {

                        showDialog(
                            getDialogErrorMessageTitle(),
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
     * Добавление объектов во временную коллекцию
     * @todo сделать колбэк с параметрами
     */
    $('._addObjectsToTempCollection').click(function(){

        var $this = $(this);
        var objectIds = [];
        $('._objectItem:checked').each(function(){
            objectIds.push($(this).data('object-id'));
        });
        // если не выбрали объекты - ничего не делаем
        if (objectIds.length == 0) {
            return false;
        }

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {

                var objectIds = [];
                $('._objectItem:checked').each(function(){
                    objectIds.push($(this).data('object-id'));
                });
                var tempCollectionId = $('._tempCollectionSelect').children(':selected').val();

                closeDialog();

                $.ajax({
                    url: '/site/ajax',
                    type: 'POST',
                    data: {
                        action: 'addObjectsToTempCollection',
                        params: {
                            objectIds: objectIds,
                            tempCollectionId: tempCollectionId
                        }
                    },
                    success: function() {
                        window.location.reload(true);
                    },
                    error: function(jqXHR) {

                        showDialog(
                            getDialogErrorMessageTitle(),
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
