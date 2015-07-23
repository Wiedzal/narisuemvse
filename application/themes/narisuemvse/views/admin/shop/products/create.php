<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'create-product-form',
    'enableAjaxValidation' => true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'beforeValidate'=>new CJavaScriptExpression('function(form) {
            for(var instanceName in CKEDITOR.instances) { 
                CKEDITOR.instances[instanceName].updateElement();
            }
            return true;
        }'),
        'afterValidate'=>'js: function(form, data, hasError) {
            if (!hasError) {
                $.ajax({
                  type: "POST",
                  url: "/admin/shop/products/create",
                  data: $("#create-product-form").serialize(),
                  beforeSend: function() {
                    $(".super-wrapper").addClass("loading");
                  },
                  complete: function() {
                      $("#tab-1").removeClass("active");
                      $(".inactive").removeClass("inactive");
                      $("#tab-2").addClass("active");
                      setTimeout( function() {
                        $(".super-wrapper").removeClass("loading");
                      } , 2500)
                  },
                  success: function(data) {
                    
                  }
                });
                return false;
            }}'),
    'stateful' => true,
    //'htmlOptions' => array('enctype' => 'multipart/form-data'),
));?>

<div class="tabs mb20">
    
    <div class="tabs-list">
        <ul class="tabs">
            <li class="active">
                <a href="javascript:void(0)">Основные данные <span class="required">*</span></a>
            </li>
            <li class="inactive">
                <a href="javascript:void(0)">Загрузка изображений</a>
            </li>
        </ul>
    </div>
    
    <div class="tab-content">
        <div class="tab-content-in active" id="tab-1">
            
            <div class="row form-group">
                <div class="col-3 form-collabel">
                    <?=Yii::t('app', 'Артикул')?> <span class="required">*</span>
                </div>
                <div class="col-9">
                    <?=$form->textField($model,'article', array('class' => 'form-input')); ?>
                    <div class="form-error">
                        <?=$form->error($model,'article');?>
                    </div>
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col-3 form-collabel">
                    <?=Yii::t('app', 'Название товара')?> <span class="required">*</span>
                </div>
                <div class="col-9">
                    <?=$form->textField($model,'title', array('class' => 'form-input')); ?>
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
                    <?=Yii::t('app', 'Поместить в')?>
                </div>
                <div class="col-9">
                    <?/*=$form->dropDownList($model,'parent_id', ShopCategories::model()->getAssocList(), array('class'=>'form-input')); */?>
                    <?=CHtml::activeListBox($model, 'category_id', ShopCategories::getAssocList()->list, array(
                        'options' => ShopCategories::getAssocList()->listOptions,
                        'class' => 'form-input',
                        'size' => 0))?>
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col-3 form-collabel">
                    <?=Yii::t('app', 'Цена')?> <span class="required">*</span>
                </div>
                <div class="col-9">
                    <?=$form->textField($model, 'price', array('class' => 'form-input')); ?>
                    <div class="form-error">
                        <?=$form->error($model,'price');?>
                    </div>
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col-3 form-collabel">
                    <?=Yii::t('app', 'Наличие')?>
                </div>
                <div class="col-8">
                    <?=$form->checkBox($model, 'is_available'); ?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-3">
                    Отображать на сайте
                </div>
                <div class="col-8">
                    <?=$form->checkBox($model, 'is_show'); ?>
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col-3 form-collabel">
                    <?=Yii::t('app', 'Описание товара')?>
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
                <?=CHtml::submitButton(Yii::t('app', 'Создать'), array('class'=>'btn btn-success'));?>
                <?=CHtml::link(Yii::t('app', 'Вернуться назад'), $this->createUrl('products/index'), array('class'=>'btn'))?>
            </div>

        </div>

        <? # ИЗОБРАЖЕНИЯ ?>
        <div class="tab-content-in" id="tab-2">

            <h3 class="mb20">Основное изображение</h3>

            <div class="col-9 form-collabel">
                <div class="mb20">
                    <img alt="" src="<?=Yii::app()->theme->baseUrl?>/public/admin/img/no-photo/500x500.png" class="w250">
                </div>
            </div>

            <hr class="mt30 mb30"/>

            <ul class="mb20">
                <li>
                    <input id="userfile" type="file" name="userfile" class="Upload"/>
                </li>
                <li>
                     <div class="progress">
                        <div id="progress_bar" class="progress-bar"></div>
                    </div>
                </li>
                <li id="errors"></li>
                <li id="file_queue" class="none"></li>
            </ul>

            <div id="photo-table" class="photo-list clearfix">
                <div id="" class="photo-img">
                    <a class="delete-photo" href="javascript:void(0)">Удалить</a>
                    <a href="">
                        <img alt="" src="<?=Yii::app()->theme->baseUrl?>/public/admin/img/no-photo/300x200.png" class="w75">
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>