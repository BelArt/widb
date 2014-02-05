$(function(){

    /**
     * Хак - для каждой строки таблицы прописываем айдишник изображения в атрибут ячейки колонки с чекбоксом.
     * Нужно для функционала удаления выбранных изображений
     */
    $('._imageLinkInTableRow').each(function(){

        var link = $(this).attr('href');
        var imageId = link.substring(link.lastIndexOf('/')+1);

        $(this).parent('td').prev('td').children('._imageItem').attr('data-image-id', imageId);

    });

    /**
     * Удаление выбранных изображений объекта
     */
    $('._deleteSelectedImages').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {

                // определяем, какие изображения удалять - собираем массив с их айдишниками
                var idsOfImagesToDelete = [];
                $('._imageItem:checked').each(function(){
                    idsOfImagesToDelete.push($(this).data('image-id'));
                });

                closeDialog();

                $.ajax({
                    url: '/site/ajax',
                    type: 'POST',
                    data: {
                        action: 'deleteImages',
                        params: {
                            ids: idsOfImagesToDelete
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
     * Удаление объекта
     */
    $('._deleteObject').click(function(){

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


    $('._addObjectToTempCollection').click(function(){

        var $this = $(this);


        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {

                var $tempCollectionSelect = $('._tempCollectionSelect');
                var objectIds = [$tempCollectionSelect.data('object-id')];
                var tempCollectionId = $tempCollectionSelect.children(':selected').val();
                //console.log(objectIds, tempCollectionId);
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
