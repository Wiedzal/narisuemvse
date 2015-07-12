<?=CHtml::beginForm(null, 'post', array('enctype' => 'multipart/form-data')) ?>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Заголовок')?> <span class="required">*</span>
    </div>
    <div class="col-9">
        <?=CHtml::activeTextField($model,'title', array('class' => 'form-input translit-string')); ?>
        <div class="form-error">
            <?=CHtml::error($model,'title');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Краткое описание')?> <span class="required">*</span>
        <h6 class="form-info text-gray"><?=Yii::t('app', 'Максимальная длина 255 символов')?></h6>
    </div>
    <div class="col-9">
        <?=CHtml::activeTextArea($model,'brief_text', array('class' => 'form-input', 'rows' => 5))?>
        <div class="form-error">
            <?=CHtml::error($model,'brief_text');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Полный текст')?>
    </div>
    <div class="col-9">
        <?=CHtml::activeTextArea($model,'text', array('id' => 'editor', 'class' => 'form-input'))?>
        <div class="form-error">
            <?=CHtml::error($model,'text');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Изображение')?>
        <h6 class="form-info text-gray"><?=Yii::t('app', 'Максимальный размер 2Mb')?></h6>
    </div>
    <div class="col-9 form-collabel">
        <? if (($model->image != NULL) && ($model->image->full_path != null)) : ?>
            <?=CHtml::image('/src', '')?>
        <? else : ?>
            <div id="image_is_miss">
                <?=Yii::t('app', 'Отсутствует')?><br/>
                <?=CHtml::link(Yii::t('app', 'Изменить'), 'javascript:void(0)', array('id' => 'link_change'))?>
            </div>
        <? endif ; ?>
        <div class="input-file" style="display:none" id="form_block">
            <input type="text" class="form-input" readonly placeholder="Файл не выбран" />
            <button class="btn">Обзор</button>
            <?=CHtml::activeFileField($model, 'image', array('class'=>'none'))?>
            <h6 class="form-info">
                <?=CHtml::link(Yii::t('app', 'Отмена'), 'javascript:void(0)', array('id'=>'link_cancel'))?>
            </h6>
        </div>
        <div class="form-error">
            <?=CHtml::error($model,'image');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3">
        <?=Yii::t('app', 'Отображать на сайте')?>
    </div>
    <div class="col-8">
        <?=CHtml::activeCheckBox($model, 'is_show'); ?>
    </div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
    <div class="col-3 form-collabel">
        Источник
    </div>
    <div class="col-9">
    
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        Ссылка на источник
    </div>
    <div class="col-9">
    
    </div>
</div>

<div class="form-actions">
    <?=CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'), array('class'=>'btn btn-success')); ?>
    <?=CHtml::link(Yii::t('app', 'Вернуться назад'), '/admin/news/default/index', array('class'=>'btn'))?>
</div>
<?=CHtml::endForm(); ?>

<script>
    //CKEDITOR.replace('editor');
    $(function() {
        $('#link_change').bind('click', function(){
            $('#image_is_miss').hide();
            $('#link_change').hide();
            $('#form_block').show();
            $('#link_cancel').show();
        });
    
        $('#link_cancel').bind('click', function(){
            $('#image').val(null);
            $('#form_block').hide();
            $('#link_cancel').hide();
            $('#image_is_miss').show();
            $('#link_change').show();
        })
    });
</script>
