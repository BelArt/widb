$(function(){

    $("._fancybox").fancybox({
        helpers : {
            title : {
                type : 'float'
            }
        }
    });

});

/**
 * Функция переформировывает ссылки на нормальные коллекции для того, чтобы "запомнить" выбор таба
 */
function reloadNormalCollectionsLinks(tab)
{
    $('._normalCollectionLink').each(function(){
        $(this).attr('href', new Uri($(this).attr('href')).replaceQueryParam('tb',tab));
    });
}
