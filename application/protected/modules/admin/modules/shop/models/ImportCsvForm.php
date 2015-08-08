<?php

/**
 * ImportCsvForm class.
 * ImportCsvForm is the data structure for keeping
 */
class ImportCsvForm extends CFormModel
{
    public $delimiter = ';';
    public $categoryId;
    public $skipFirstLine = false;
    public $csvFile;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('csvFile', 'FileValidator', 'allowEmpty'=>false, 'types'=>'csv', 'maxSize'=>1024 * 1024 * 10),
            array('categoryId', 'numerical', 'integerOnly'=>true),
            array('delimiter', 'required'),
            array('skipFirstLine', 'boolean'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'skipFirstLine' => Yii::t('app', 'Игнорировать первую строчку'),
            'delimiter' => Yii::t('app', 'Разделитель полей'),
            'categoryId' => Yii::t('app', 'Категория'),
            'csvFile' => Yii::t('app', 'CSV документ'),
        );
    }

    /**
     * Возвращает массив объектов с информацией об открытых для импортирования столбцах таблицы
     */
    public function getAvailableColumns($table)
    {
        //var_dump(ShopProducts::model()->attributes);die;
        $schema = ShopProducts::model()->tableSchema;
        $tech_columns = array(
            'id'=>'id',
            'category_id'=>'category_id',
            //'main_image_id'=>'main_image_id',
            'created_at'=>'created_at',
            'created_by'=>'created_by',
            'modified_at'=>'modified_at',
            'modified_by'=>'modified_by',
        );
        $columns = array_values(array_diff_key($schema->columns, $tech_columns));
        
        $firstLine = array_map(function($element){return $element->comment;}, $columns);
        $exampleLine = array_map(function($element){return $element->name;}, $columns);
        
        $exampleLine = array_flip($exampleLine);
        
        var_dump($firstLine+$exampleLine);
        var_dump($firstLine);//die;
        var_dump($exampleLine);//die;
        var_dump($columns);die;
        return $columns;
    }
}
