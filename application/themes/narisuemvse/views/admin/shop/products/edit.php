<div class="tabs mb20">

    <div class="tabs-list">
        <ul class="tabs">
            <li class="active">
                <a href="javascript:void(0)">Основные данные <span class="required">*</span></a>
            </li>
            <li class="">
                <a href="javascript:void(0)">Загрузка изображений</a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-content-in active" id="tab-1">

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
                    <?php /*$this->widget('application.extensions.ckeditor.ECKEditor', array(
                        'model'=>$model,
                        'attribute' => 'description',
                        'language' => 'ru',
                        'editorTemplate' => 'full',
                        'height' => '300px'));*/?>
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

            <?php $this->endWidget(); ?>

        </div>

        <? # ИЗОБРАЖЕНИЯ ?>
        <div class="tab-content-in" id="tab-2">

            <h3 class="mb20">Основное изображение *</h3>

            <div class="row form-group">
                <div class="mb20 col-9">
                    <? if($model->imageThumbUrl) : ?>
                        <?=CHtml::link(CHtml::image($model->imageThumbUrl, $model->title), $model->imageUrl); ?>
                        <br/>
                        <?=CHtml::link(Yii::t('app', 'Убрать'), "#", array(
                            'submit' => $this->createUrl('default/deletePicture', array('id' => $model->id)),
                            'params' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                            'confirm' => Yii::t('app', 'Убрать основное изображение?'),
                            'onclick' => 'onPictureDelete()'));
                        ?>
                    <? else : ?>
                        <?=CHtml::image(Yii::app()->theme->baseUrl.'/public/admin/img/no-photo/500x500.png','',array('class'=>'w250'))?>
                    <? endif; ?>
                    <br/><br/>
                    <span>* выберите изображение из уже загруженных в блоке ниже либо сначала загрузите нужное.</span>
                    <!--<span>* основное изображение выбирается из уже загруженных для этого продукта.</span>-->
                </div>
            </div>

            <hr class="mt30 mb30"/>

            <h3 class="mb20">Загруженные изображения</h3>

            <?php $this->widget('xupload.XUpload', array(
                'url' => $this->createUrl('products/upload',array('id'=>$model->id)),
                'autoUpload' => true,
                'model' => $XUploadForm,
                'attribute' => 'file',
                'multiple' => true,
                'formView' => Yii::app()->theme->baseUrl.'/views/admin/shop/products/form.php',
            ));?>

            <div id="images-block" class="photo-list row clearfix">
                <? if (!empty($model->images)) : ?>
                    <? foreach ($model->images as $image) : ?>
                        <div data-wrap="image" class="col-3 photo-img">
                            <div class="photo-img-in">
                                <a href="javascript:void(0)" data-delete="<?=$image->id?>" class="delete-photo btn btn-icon">
                                    <i class="fa fa-times"></i>
                                </a>
                                <a data-toggle="vix" class="photo-img-link" href="<?=$image->imageUrl?>">
                                    <?=CHtml::image($image->imageThumbUrl, $image->file_name)?>
                                </a>
                            </div>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

        </div>
    </div>
</div>