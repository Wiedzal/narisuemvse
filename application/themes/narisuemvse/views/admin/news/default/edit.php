<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'create-news-form',
    //'enableAjaxValidation' => true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'beforeValidate' => new CJavaScriptExpression('function(form) {
            for(var instanceName in CKEDITOR.instances) { 
                CKEDITOR.instances[instanceName].updateElement();
            }
            return true;
        }'),
    ),
    'stateful' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Заголовок')?> <span class="required">*</span>
    </div>
    <div class="col-9">
        <?=$form->textField($model,'title', array('class' => 'form-input translit-string')); ?>
        <div class="form-error">
            <?=$form->error($model,'title');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Краткое описание')?> <span class="required">*</span>
        <h6 class="form-info text-gray"><?=Yii::t('app', 'Максимальная длина 255 символов')?></h6>
    </div>
    <div class="col-9">
        <?=$form->textArea($model,'brief_text', array('class' => 'form-input', 'rows' => 5))?>
        <div class="form-error">
            <?=$form->error($model,'brief_text');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Полный текст')?>
    </div>
    <div class="col-9 form-collabel">
        <?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
            'model'=>$model,
            'attribute' => 'text',
            'language' => 'ru',
            'editorTemplate' => 'full',
            'height' => '300px'));?>
        <div class="form-error">
            <?=$form->error($model,'text');?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Изображение')?>
        <h6 class="form-info text-gray"><?=Yii::t('app', 'Максимальный размер 2MB')?></h6>
    </div>
    <div class="col-9 form-collabel">
        <? if($model->imageUrl) : ?>
            <div class="mb20">
                <?=CHtml::link(CHtml::image($model->imageThumbUrl, $model->title), $model->imageUrl); ?>
                <br/>
                <?=CHtml::link(Yii::t('app', 'Изменить'), 'javascript:void(0)', array('id' => 'link-change'))?>
                
                <?=CHtml::link(Yii::t('app', 'Удалить'), "#", array(
                    'submit' => $this->createUrl('default/deletePicture', array('id' => $model->id)),
                    'params' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                    'confirm' => Yii::t('app', 'Удалить изображение?'),
                    'onclick' => 'onPictureDelete()'));
                ?>
            </div>
        <? else : ?>
            <div id="image-is-miss">
                <?=Yii::t('app', 'Отсутствует')?><br/>
                <?=CHtml::link(Yii::t('app', 'Изменить'), 'javascript:void(0)', array('id' => 'link-change'))?>
            </div>
        <? endif ; ?>
        <div class="input-file" style="display:none" id="form-block">
            <input id="image-field" type="text" class="form-input" placeholder="<?=Yii::t('app', 'Файл не выбран')?>" readonly />
            <button class="btn"><?=Yii::t('app', 'Обзор')?></button>
            <?=$form->fileField($model, 'image', array('class'=>'none'))?>
            <h6 class="form-info">
                <?=CHtml::link(Yii::t('app', 'Отмена'), 'javascript:void(0)', array('id'=>'link-cancel'))?>
            </h6>
        </div>
        <div class="form-error">
            <?=$form->error($model,'image',array('id'=>'image-error'));?>
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="col-3">
        <?=Yii::t('app', 'Отображать на сайте')?>
    </div>
    <div class="col-8">
        <?=$form->checkBox($model, 'is_show'); ?>
    </div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Источник')?>
    </div>
    <div class="col-9">
    
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Ссылка на источник')?>
    </div>
    <div class="col-9">
    
    </div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Описание (для поисковых систем)')?>
    </div>
    <div class="col-9">
    
    </div>
</div>
<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Ключевые слова (для поисковых систем)')?>
    </div>
    <div class="col-9">
    
    </div>
</div>

<div class="form-actions">
    <?=CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'), array('class'=>'btn btn-success')); ?>
    <?=CHtml::link(Yii::t('app', 'Вернуться назад'), $this->createUrl('default/index'), array('class'=>'btn'))?>
</div>
<?php $this->endWidget(); ?>