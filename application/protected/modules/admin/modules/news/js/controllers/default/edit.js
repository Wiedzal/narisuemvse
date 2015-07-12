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
        $('#News_image').val('');
        $('#image-error').hide();
        $('#form-block').hide();
        $('#link-cancel').hide();
        $('#image-is-miss').show();
        $('#link-change').show();
    })
});

function onPictureDelete()
{
    $('#create-news-form').off();
}