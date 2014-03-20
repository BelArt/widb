$(function(){

    /**
     * Удаление изображения
     */
    $('._deleteImage').click(function(){

        var $this = $(this);

        showDialog(
            $this.data('dialog-title'),
            $this.data('dialog-message'),
            function() {
                window.location = $this.attr('href');
            },
            function() {
                closeDialog();
            }
        );

        return false;
    });

});
