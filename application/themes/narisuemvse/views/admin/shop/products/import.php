<div class="mb20">
    
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'import-form',
        //'enableAjaxValidation' => true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true
        ),
        'stateful' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));?>
    
    <div class="row form-group">
        <div class="col-3 form-collabel">
            <?=Yii::t('app', 'Разделитель полей')?> <span class="required">*</span>
        </div>
        <div class="col-9">
            <?=$form->textField($model,'delimiter', array('class' => 'form-input')); ?>
            <div class="form-error">
                <?=$form->error($model,'delimiter');?>
            </div>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-3">
            <?=Yii::t('app', 'Категория')?>
        </div>
        <div class="col-9">
            <?/*=$form->dropDownList($model,'parent_id', ShopCategories::model()->getAssocList(), array('class'=>'form-input')); */?>
            <?=CHtml::activeListBox($model, 'categoryId', ShopCategories::getAssocList()->list, array(
                'options' => ShopCategories::getAssocList()->listOptions,
                'class' => 'form-input',
                'size' => 0))?>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-3">
            <?=Yii::t('app', 'Игнорировать первую строчку')?>
        </div>
        <div class="col-9">
            <?=$form->checkBox($model, 'skipFirstLine'); ?>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-3 form-collabel">
            <?=Yii::t('app', 'CSV документ')?> <span class="required">*</span>
        </div>
        <div class="col-9">
            <div class="input-file" id="form-block">
                <input id="image-field" type="text" class="form-input" placeholder="<?=Yii::t('app', 'Файл не выбран')?>" readonly />
                <button class="btn"><?=Yii::t('app', 'Обзор')?></button>
                <?=$form->fileField($model, 'csvFile', array('class'=>'none'))?>
            </div>
            <div class="form-error">
                <?=$form->error($model,'csvFile',array('id'=>'csvfile-error'));?>
            </div>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-9">
            <div class="form-error">
                <?= CHtml::errorSummary($errors, 'Обнаружены следующие ошибки:<br><br>');?>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <?=CHtml::submitButton(Yii::t('app', 'Поехали'), array('class'=>'btn btn-success'));?>
        <?=CHtml::link(Yii::t('app', 'Вернуться назад'), $this->createUrl('products/index'), array('class'=>'btn'))?>
        <?=CHtml::link(Yii::t('app', 'Скачать схему документа'), $this->createUrl('products/csvdownload'), array('class'=>'btn'))?>
    </div>
    
    <?php $this->endWidget(); ?>
</div>