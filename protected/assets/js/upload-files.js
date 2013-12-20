$(function() {
    window.onbeforeunload = function() {
        $.ajax({
            url: '/site/ajax',
            type: 'POST',
            async: false,
            data: {
                action: 'clearUserUploads'
            }
        });
        //return false;
    }
});