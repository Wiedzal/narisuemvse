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

            <div data-notice="edit" class="note note-success" style="display:none">
                <div class="note-close"></div>
                <i class="fa fa-fw fa-check"></i>Изменения успешно сохранены.
            </div>
        
            <?=$this->renderPartial('form_main',array(
                'model' => $model,
                'action' => 'edit'
            ));?>

        </div>

        <? # ИЗОБРАЖЕНИЯ ?>
        <div class="tab-content-in" id="tab-2">

            <h3 class="mb20">Основное изображение *</h3>

            <div class="row form-group">
                <div class="mb20 col-9">
                    <? if($model->imageThumbUrl) : ?>
                        <?=CHtml::image($model->imageThumbUrl, $model->title, array(
                            'id'=>'main-image',
                            'class'=>'w250',
                        ));?>
                        <br/>
                        <a href="javascript:void(0)" id="clear-main-image" data-product-id=<?=$model->id?>>Убрать</a>
                    <? else : ?>
                        <?=CHtml::image(Yii::app()->theme->baseUrl.'/public/admin/img/no-photo/500x500.png', $model->title, array(
                            'id'=>'main-image',
                            'class'=>'w250',
                        ));?>
                        <br/>
                        <a href="javascript:void(0)" id="clear-main-image" style="display:none" data-product-id="<?=$model->id?>">Убрать</a>
                    <? endif; ?>
                    <br/><br/>
                    <span>* выберите изображение из уже загруженных в блоке ниже либо сначала загрузите нужное.</span>
                    <!--<span>* основное изображение выбирается из уже загруженных для этого товара.</span>-->
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
                'formView' => Yii::app()->theme->baseUrl.'/views/admin/shop/products/form_upload.php',
            ));?>

            <div id="images-block" class="files photo-list row clearfix">
                <? if (!empty($model->images)) : ?>
                    <? foreach ($model->images as $image) : ?>
                        <div class="col-3 photo-img">
                            <div class="photo-img-in">
                                <a href="javascript:void(0)" data-action="delete" data-id="<?=$image->id?>" class="delete-photo btn btn-icon">
                                    <i class="fa fa-times"></i>
                                </a>
                                <a href="<?=$image->imageUrl?>" class="photo-img-link">
                                    <?=CHtml::image($image->imageThumbUrl, $image->file_name)?>
                                </a>
                            </div>
                            <a href="javascript:void(0)" data-action="main" data-id="<?=$image->id?>">Сделать основным</a>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>
            </div>

        </div>
    </div>
</div>