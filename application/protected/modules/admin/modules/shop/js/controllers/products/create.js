$(function() 
{
    var fileName;
    $('#News_image').change(function() {
        if(fileName == this.value) {
            $('#image-error').show();
        }
        fileName = this.value;
    });
    
    $('#link-change').bind('click', function() {
        $('#image-is-miss').hide();
        $('#link-change').hide();
        $('#form-block').show();
        $('#link-cancel').show();
    });

    $('#link-cancel').bind('click', function() {
        $('#image-field').val('');
        $('#image-error').hide();
        $('#form-block').hide();
        $('#link-cancel').hide();
        $('#image-is-miss').show();
        $('#link-change').show();
    });
    
    $('ul.tabs').on('click', 'li:not(.active)', function() {  
        $(this).addClass('active').siblings().removeClass('active');  
        $('.tab-content').find('div.tab-content-in').eq($(this).index()).fadeIn(500).siblings('div.tab-content-in').hide();
    });
});