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
            
            <div data-notice="edit" class="note note-success" style="display:none">
                <div class="note-close"></div>
               <i class="fa fa-fw fa-check"></i>Изменения успешно сохранены.
            </div>
            
            <?=$this->renderPartial('form_main',array(
                'model' => $model,
                'action' => 'create'
            ));?>

        </div>

        <? # ИЗОБРАЖЕНИЯ ?>
        <div class="tab-content-in" id="tab-2">

            <div data-notice="create" class="note note-success" style="display:none">
                <div class="note-close"></div>
                <i class="fa fa-fw fa-check"></i>Товар успешно создан.
            </div>
            
            <h3 class="mb20">Основное изображение *</h3>

            <div class="row form-group">
                <div class="mb20 col-9">
                    <?=CHtml::image(Yii::app()->theme->baseUrl.'/public/admin/img/no-photo/500x500.png', '', array(
                        'id'=>'main-image',
                        'class'=>'w250',
                    ));?>
                    <br/>
                    <a href="javascript:void(0)" id="clear-main-image" style="display:none" data-product-id="<?=$model->id?>">Убрать</a>
                    <br/><br/>
                    <span>* выберите изображение из уже загруженных в блоке ниже либо сначала загрузите нужное.</span>
                    <!--<span>* основное изображение выбирается из уже загруженных для этого товара.</span>-->
                </div>
            </div>

            <hr class="mt30 mb30"/>

            <h3 class="mb20">Загруженные изображения</h3>

            <?php $this->widget('xupload.XUpload', array(
                'url' => '', //url вместе с id созданного товара подставляем в CActiveForm в afterValidate
                'autoUpload' => true,
                'model' => $XUploadForm,
                'attribute' => 'file',
                'multiple' => true,
                'formView' => 'form_upload',
            ));?>

            <div id="images-block" class="files photo-list row clearfix">
                
            </div>

        </div>
    </div>
</div>