$(function(){
    $('._perPageToggleSelect').change(function(){
        var $this = $(this);
        window.location.href = new Uri(window.location.href).replaceQueryParam($this.data('var-name'), $this.val());
    });
});

