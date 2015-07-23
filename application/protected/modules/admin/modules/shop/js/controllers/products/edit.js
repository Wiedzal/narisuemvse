$(function() 
{
    $('ul.tabs').on('click', 'li:not(.active)', function() {  
        $(this).addClass('active').siblings().removeClass('active');  
        $('.tab-content').find('div.tab-content-in').eq($(this).index()).fadeIn(500).siblings('div.tab-content-in').hide();
    });

    $('#images-block').click(function(event) 
    {
        if(!confirm('Удалить изображение?'))
            return false;
        var target = event.target;
        while (target != this) 
        {
            var id = $(target).data('delete')
            if(id) 
            {
                $.ajax({
                    type  : "POST",
                    url   : '/admin/shop/products/ajaxDeleteImage',
                    data  : {
                        YII_CSRF_TOKEN : globalCsrfToken,
                        id : id,
                    },
                    cashe   : false,
                    error   : function () {
                        alert('Ошибка запроса. Обновите страницу и попробуйте ещё раз.');
                    },
                    success : function(data) {
                        if(data.status == false) {
                            alert(data.message);
                        } else {
                            $(target).closest('[data-wrap]').remove();
                        }
                    },
                });
                return;
            }
            target = target.parentNode;
        }
    });
});
