$(function(){
    $('._perPageToggleSelect').change(function(){
        var $this = $(this);
        var newUrl = new Uri(window.location.href)
            .replaceQueryParam($this.data('var-name'), $this.val())
            .replaceQueryParam('tb', 'ob');
        window.location.href = newUrl;
    });
});

