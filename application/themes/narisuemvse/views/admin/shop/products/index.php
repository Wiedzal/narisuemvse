<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'products-grid',
    'dataProvider'=> $model->search(),
    'filter'=>$model,
    'ajaxUpdate' => true,
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
            'name' => 'mainImageFileName',
            'value' => '$data->imageThumbUrl ? CHtml::image($data->imageThumbUrl, $data->title, array("class"=>"w75")) : CHtml::image(Yii::app()->theme->baseUrl . "/public/admin/img/no-photo/300x200.png", "", array("class"=>"w75"))',
            'type' => 'html',
        ),
        'article',
        'title',
        'price',
        'is_show',
        array(
            'name' => 'category_id',
            'value' => '$data->category->title',
        ),
        
        array(
            'header' => 'Операции',
            'class' => 'CButtonColumn',
            'template' => '{view} {update} {delete}',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("ajaxproducts/delete", array("id" => $data->primaryKey))'
        ),
    ),
));