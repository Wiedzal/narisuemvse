function rightHeight() {
    h = $(window).height() - $('.header').outerHeight() - $('.footer').outerHeight() - 30;
    if($('.left-side').height() > h) {
        h = $('.left-side').height()
    }
    $('.right-side-in').css('min-height', h);
}

$(window).load(function(){
    rightHeight()
})

$(window).resize(function(){
    rightHeight()
})

$(document).ready(function(){
    $('select.form-input').styler();
    
    //$('[data-toggle="tooltip"], .tooltips').tooltip();
    
    $('input[type="checkbox"]').each(function(){
        $(this).wrap('<div class="checker '+$(this).attr('data-class')+'"></div>');
        $(this).after('<div class="checker-view"></div>');
    })
    
    $('input[type="radio"]').each(function(){
        $(this).wrap('<div class="radio '+$(this).attr('data-class')+'"></div>');
        $(this).after('<div class="radio-view"></div>');
    })
    
    $('.input-file .btn').click(function(e){
        e.preventDefault();
        $(this).closest('.input-file').find('input[type="file"]').click();
        return false;
    })
    
    $('.input-file input[type="file"]').change(function(){
        val = $(this).val().split("\\");
        $(this).closest('.input-file').find('.form-input').val(val[val.length-1]);
    })
    
    $('.note-close').click(function(){
        $(this).closest('.note').hide();
    })
    
    $('.tabs-list a').click(function(){
        t = $(this).attr('href');
        $(this).closest('.tabs-list').find('a').removeClass('active');
        $(this).addClass('active');
        $(t).fadeIn(300).siblings().hide();
    })
    
})