<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'product-form',
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
                    dataType : "json",
                    url: "/admin/shop/products/create",
                    data: $("#product-form").serialize(),
                    beforeSend: function() {
                        $("html,body").scrollTop(0);
                        $(".super-wrapper").addClass("loading");
                    },
                    success: function(data) {
                        setTimeout( function() {
                            var action = $(form).data("action");
                            if(action == "create")
                            {
                                $("#ShopProducts_id").val(data.id);
                                $("#create-product-form").data("product-id", data.id).attr("product-id", data.id);
                                $("#clear-main-image").data("product-id", data.id).attr("data-product-id", data.id);
                                $(form).data("action", "edit").attr("action", "edit");
                                $("#XUploadForm-form").attr("action", "/admin/shop/products/upload/id/"+data.id);
                                $(".active").removeClass("active");
                                $(".inactive").addClass("active");
                                $(".inactive").removeClass("inactive");
                                $("#tab-2").addClass("active");
                                history.pushState(null, null, "/admin/shop/products/edit/id/"+data.id);
                            }
                            $("[data-notice="+action+"]").show();
                            $(".super-wrapper").removeClass("loading");
                        }, 1500)
                    },
                });
                return false;
            }
        }'),
    'stateful' => true,
    'htmlOptions'=>array(
        'data-action'=>$action,
    ),
    //'htmlOptions' => array('enctype' => 'multipart/form-data'),
));?>
    
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
        <?=$form->hiddenField($model, 'id');?>
        <?=CHtml::submitButton(Yii::t('app', 'Создать'), array('class'=>'btn btn-success'));?>
        <?=CHtml::link(Yii::t('app', 'Вернуться назад'), $this->createUrl('products/index'), array('class'=>'btn'))?>
    </div>
    
<?php $this->endWidget(); ?>