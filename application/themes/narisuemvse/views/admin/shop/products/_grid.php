<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'products-grid',
    'dataProvider' => $model->search(),
    'filter'=>$model,
    'ajaxUpdate' => true,
    'ajaxUrl' => Yii::app()->createUrl('/admin/shop/products/index'),
    'enableSorting' => array('id','article','title','price'),
    'pager' => array(  
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
            'name' => 'id',
            'value' => '$data->id',
            'filter' => false,
        ),
        array(
            'name' => '_main_mage_file_name',
            'value' => '$data->imageThumbUrl ? CHtml::link(CHtml::image($data->imageThumbUrl, $data->title, array("class"=>"w75")), $data->imageUrl) : CHtml::image(Yii::app()->theme->baseUrl . "/public/admin/img/no-photo/300x200.png", "", array("class"=>"w75"))',
            'type' => 'html',
            'filter' => false,
        ),
        array(
            'name' => 'article',
            'value' => '$data->article',
            //'filter' => false,
        ),
        array(
            'name' => 'title',
            'value' => '$data->title',
            //'filter' => false,
        ),
        array(
            'name' => 'price',
            'value' => '$data->price',
            'filter' => false,
        ),
        array(
            'name' => 'category_id',
            'value' => '$data->category->title',
            //'filter' => false,
        ),
        array(
            'name' => 'is_show',
            'value' => 'ShopProducts::gridIsShowItem($data->is_show)',
            'filter' => ShopProducts::gridIsShowItems(),
        ),
        
        array(
            'header' => 'Операции',
            'class' => 'EButtonColumnWithClearFilters',
            'label' => 'Сбросить все фильтры',
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
            'template' => '{view}{update}{delete}',
            'updateButtonUrl' => 'Yii::app()->controller->createUrl("products/edit", array("id" => $data->primaryKey))',
            //'updateButtonImageUrl' => Yii::app()->theme->baseUrl.'/public/admin/img/cgrid-edit.png',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("products/delete", array("id" => $data->primaryKey))',
        ),
    ),
));
?>