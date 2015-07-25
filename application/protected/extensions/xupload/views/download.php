<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="col-3 photo-img">
        <div class="photo-img-in">
            {% if (file.error) { %}
                <div class="photo-error">
                    <span class="label label-important">{%=locale.fileupload.error%}</span>
                    <span class="photo-error-message">{%=locale.fileupload.errors[file.error] || file.error%}</span>
                </div>
            {% } else { %}
                <a href="javascript:void(0)" data-action="delete" data-id="{%=file.id%}" class="delete-photo btn btn-icon">
                    <i class="fa fa-times"></i>
                </a>
                {% if (file.thumbnail_url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" class="photo-img-link">
                        <img src="{%=file.thumbnail_url%}">
                    </a>
                {% } %}
            {% } %}
        </div>
        <a href="javascript:void(0)" data-action="main" data-id="{%=file.id%}">Сделать главным</a>
    </div>
{% } %}
</script>

