<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'create-category-form',
    'enableAjaxValidation' => true,
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
        <?=Yii::t('app', 'Название категории')?> <span class="required">*</span>
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
        <?=Yii::t('app', 'Псевдоним')?> <span class="required">*</span>
        <h6 class="text-gray h6"><?=Yii::t('app', 'Допустимы только латинские буквы, цифры и тире')?></h6>
    </div>
    <div class="col-9">
        <?=$form->textField($model,'alias', array('class' => 'form-input')); ?>
        <div class="form-error">
            <?=$form->error($model,'alias');?>
        </div>
    </div>
</div>

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Порядок следования')?>
    </div>
    <div class="col-9">
        
    </div>
</div>

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Поместить в')?>
    </div>
    <div class="col-9">
        <?/*=$form->dropDownList($model,'parent_id', ShopCategories::model()->getAssocList(), array('class'=>'form-input')); */?>
        <?=CHtml::activeListBox($model, 'parent_id', $possibleParents->list, array(
            'options' => $possibleParents->listOptions,
            'class' => 'form-input',
            'size' => 0))?>
    </div>
</div>

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Отобразить при выборе категории')?>
    </div>
    <div class="col-9 form-collabel">
        <?=$form->radioButtonList($model,'is_content_page',$model->getMappingList(),array('separator'=>' '))?>
    </div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Описание категории')?>
    </div>
    <div class="col-9 form-collabel">
        <?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
            'model'=>$model,
            'attribute' => 'description',
            'language' => 'ru',
            'editorTemplate' => 'full',
            'height' => '300px'));?>
        <div class="form-error">
            <?=$form->error($model,'description');?>
        </div>
    </div>
</div>

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Изображение')?>
        <h6 class="form-info text-gray"><?=Yii::t('app', 'Максимальный размер 2MB')?></h6>
    </div>
    <div class="col-9 form-collabel">
        <? if($model->imageThumbUrl) : ?>
            <div class="mb20">
                <?=CHtml::link(CHtml::image($model->imageThumbUrl, $model->title), $model->imageUrl); ?><br/>
                <?=CHtml::link(Yii::t('app', 'Изменить'), 'javascript:void(0)', array('id' => 'link-change'))?>
                
                <?=CHtml::link(Yii::t('app', 'Удалить'), "#", array(
                    'submit' => $this->createUrl('categories/deletePicture', array('id' => $model->id)),
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
        <?=Yii::t('app', 'Описание (для поисковых систем)')?>
    </div>
    <div class="col-9">
        <?=$form->textArea($model,'meta_description', array('class' => 'form-input', 'row' => 2)); ?>
        <div class="form-error">
            <?=$form->error($model,'meta_description');?>
        </div>
    </div>
</div>

<div class="row form-group">
    <div class="col-3 form-collabel">
        <?=Yii::t('app', 'Ключевые слова (для поисковых систем)')?>
    </div>
    <div class="col-9">
        <?=$form->textField($model,'meta_keywords', array('class' => 'form-input')); ?>
        <div class="form-error">
            <?=$form->error($model,'meta_keywords');?>
        </div>
    </div>
</div>

<div class="form-actions">
    <?=CHtml::submitButton(Yii::t('app', 'Сохранить'), array('class'=>'btn btn-success')); ?>
    <?=CHtml::link(Yii::t('app', 'Вернуться назад'), $this->createUrl('categories/index'), array('class'=>'btn'))?>
</div>

<?php $this->endWidget(); ?>
