<? if(empty($model->search()->itemCount)) : ?>
    <div class="note note-info">
        <?=Yii::t('app', 'У вас ещё не создано ни одного товара. Вы можете')?> <a href="<?=$this->createUrl('products/create')?>" class="medium"><?=Yii::t('app', 'создать товар')?></a>
    </div>
<? else : ?>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'products-grid',
        'dataProvider'=> $model->search(),
        'filter'=>$model,
        'ajaxUpdate' => true,
        'enableSorting' => false,
        //'ajaxUrl' => Yii::app()->createUrl('/admin/catalog/products/index'),
        'pager'=> array(  
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>', 
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'header' => '',
            'htmlOptions' => array(
                'class' => 'pagination'
            ), 
        ), 
        'htmlOptions' => array(
            //'class' => 'tablesorter'
        ),
        'columns'=>array(
            array(
                'name' => '_main_mage_file_name',
                'value' => '$data->imageThumbUrl ? CHtml::link(CHtml::image($data->imageThumbUrl, $data->title, array("class"=>"w75")), $data->imageUrl) : CHtml::image(Yii::app()->theme->baseUrl . "/public/admin/img/no-photo/300x200.png", "", array("class"=>"w75"))',
                'type' => 'html',
                'filter' => false,
            ),
            array(
                'name' => 'article',
                'type' => 'boolean',
                'value' => '$data->article',
            ),
            array(
                'name' => 'title',
                'value' => '$data->title',
            ),
            array(
                'name' => 'price',
                'value' => '$data->price',
                'filter' => CHtml::textField('ShopProducts[_price_min]', $model->_price_min, array('style'=>'width: 100px; height: 20px;')) .' - '. CHtml::textField('ShopProducts[_price_max]', $model->_price_max, array('style'=>'width: 100px; height: 20px;')) 
            ),
            array(
                'name' => 'is_show',
                'value' => 'ShopProducts::gridIsShowItem($data->is_show)',
                'filter' => ShopProducts::gridIsShowItems(),
            ),
            array(
                'name' => 'category_id',
                'value' => '$data->category->title',
            ),
            
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                // 'buttons'=>array(
                    // 'show'=>array(
                        // 'label'=>'<i class="fa fa-eye"></i>',
                        // 'url'=>'Yii::app()->controller->createUrl("products/edit", array("id"=>$data->primaryKey))',
                        // 'options'=> array(/*"class"=>"btn btn-info btn-icon",*/ 'title'=>'Просмотр'),
                    // ),
                    // 'edit'=>array(
                        // 'label'=>'<i class="fa fa-pencil"></i>',
                        // 'url'=>'Yii::app()->controller->createUrl("products/edit", array("id"=>$data->primaryKey))',
                        // 'options'=> array(/*"class"=>"btn btn-info btn-icon",*/ 'title'=>'Редактировать'),
                    // ),
                    // 'remove'=>array(
                        // 'label'=>'<i class="fa fa-trash"></i>',
                        // 'url'=>'Yii::app()->controller->createUrl("products/ajaxDelete", array("id"=>$data->primaryKey))',
                        // 'options'=> array(/*"class"=>"btn btn-info btn-icon",*/ 'title'=>'Удалить'),
                    // ),
                // ),
                'template'=>'{view}{update}{delete}',
                'updateButtonUrl' => 'Yii::app()->controller->createUrl("products/edit", array("id" => $data->primaryKey))',
                //'updateButtonImageUrl' => Yii::app()->theme->baseUrl.'/public/admin/img/cgrid-edit.png',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("ajaxproducts/delete", array("id" => $data->primaryKey))',
            ),
        ),
    ));
    ?>
    
    <div class="form-actions mt20">
        <a href="<?=$this->createUrl('products/create')?>" class="btn btn-success"><?=Yii::t('app', 'Добавить товар')?></a>
    </div>
<? endif; ?>