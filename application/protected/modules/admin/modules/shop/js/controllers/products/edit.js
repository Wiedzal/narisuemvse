$(function() 
{
    $('ul.tabs').on('click', 'li:not(.active)', function() {  
        $(this).addClass('active').siblings().removeClass('active');  
        $('.tab-content').find('div.tab-content-in').eq($(this).index()).fadeIn(500).siblings('div.tab-content-in').hide();
    });
    
    $('#clear-main-image').click(function(event) {
        clearMainImage(event.target.getAttribute('data-product-id'));
    });
    
    $('#remotefile-form btn').click(function(event) {
        event.preventDefault();
        var url = $('#remotefile-form ').val();
        $.ajax({
            type : "POST",
            url : '/admin/shop/products/uploadFromUrl',
            data : {
                YII_CSRF_TOKEN : globalCsrfToken,
                url : url,
            },
            cashe : false,
            error : function () {
                alert('Ошибка запроса. Обновите страницу и попробуйте ещё раз.');
            },
            dataType : 'json',
            success : function(data) {
                console.log('1');
                /*if(data.status == false) {
                    alert(data.message);
                } 
                else if(data.status == true) {
                    $(target).closest('.photo-img').remove();
                    if(data.is_main == true) {
                        $('#main-image').attr('src', '/themes/narisuemvse/public/admin/img/no-photo/500x500.png');
                        $('#clear-main-image').hide();
                    }
                }*/
            },
        });
    });

    $('#images-block').click(function(event) {
        var target = event.target;
        while (target != this) 
        {
            var action = target.getAttribute('data-action');
            if(action) 
            {
                switch (action) 
                {
                    case 'delete':
                        deleteImage(target);
                        break;
                    case 'main':
                        updateMainImage(target);
                        break;
                    default :
                        break;
                }
            }
            target = target.parentNode;
        }
    });
});

function deleteImage(target)
{
    if(!confirm('Удалить изображение?'))
        return false;
        
    var id = target.getAttribute('data-id');
    $.ajax({
        type  : "POST",
        url   : '/admin/shop/products/ajaxDeleteImage',
        data  : {
            YII_CSRF_TOKEN : globalCsrfToken,
            id : id,
        },
        cashe : false,
        error : function () {
            alert('Ошибка запроса. Обновите страницу и попробуйте ещё раз.');
        },
        dataType : 'json',
        success : function(data) {
            if(data.status == false) {
                alert(data.message);
            } 
            else if(data.status == true) {
                $(target).closest('.photo-img').remove();
                if(data.is_main == true) {
                    $('#main-image').attr('src', '/themes/narisuemvse/public/admin/img/no-photo/500x500.png');
                    $('#clear-main-image').hide();
                }
            }
        },
    });
    return;
}

function updateMainImage(target)
{
    if(!confirm('Назначить основным?'))
        return false;
        
    var id = target.getAttribute('data-id');
    $.ajax({
        type  : "POST",
        url   : '/admin/shop/products/ajaxUpdateMainImage',
        data  : {
            YII_CSRF_TOKEN : globalCsrfToken,
            id : id,
        },
        cashe : false,
        error : function () {
            alert('Ошибка запроса. Обновите страницу и попробуйте ещё раз.');
        },
        dataType : 'json',
        success : function(data) {
            if(data.status == false) {
                alert(data.message);
            } 
            else if(data.status == true) {
                $('#main-image').attr('src', data.source);
                $('#clear-main-image').show();
            }
        },
    });
    return;
}

function clearMainImage(id)
{
    if(!confirm('Убрать основное изображение?'))
        return false;

    $.ajax({
        type  : "POST",
        url   : '/admin/shop/products/ajaxClearMainImage',
        data  : {
            YII_CSRF_TOKEN : globalCsrfToken,
            id : id,
        },
        cashe : false,
        error : function () {
            alert('Ошибка запроса. Обновите страницу и попробуйте ещё раз.');
        },
        dataType : 'json',
        success : function(data) {
            if(data.status == false) {
                alert(data.message);
            } 
            else if(data.status == true) {
                $('#main-image').attr('src', '/themes/narisuemvse/public/admin/img/no-photo/500x500.png');
                $('#clear-main-image').hide();
            }
        },
    });
    return;
}

// var onAfterValidate = function(form, data, hasError) {
    // if (!hasError) {
        // $.ajax({
            // type: "POST",
            // url: "/admin/shop/products/edit/id/"+data.id,
            // data: $("#edit-product-form").serialize(),
            // beforeSend: function() {
                // $("html,body").scrollTop(0);
                // $(".super-wrapper").addClass("loading");
            // },
            // success: function(data) {
                // setTimeout( function() {
                    // $(".super-wrapper").removeClass("loading");
                    // $(".note-success").show();
                // } , 1500)
            // },
        // });
        // return false;
    // }
// }