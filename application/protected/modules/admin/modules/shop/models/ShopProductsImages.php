<?php

/**
 * This is the model class for table "shop_products_images".
 *
 * The followings are the available columns in table 'shop_products_images':
 * @property integer $id
 * @property integer $product_id
 * @property integer $is_remote_file
 * @property string $mime_type
 * @property string $file_name
 * @property double $file_size
 * @property string $orig_name
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 */
class ShopProductsImages extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_products_images';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_id', 'required'),
            array('product_id, is_remote_file, file_size, created_by, modified_by', 'numerical', 'integerOnly'=>true),
            array('mime_type', 'length', 'max'=>100),
            array('file_name, orig_name', 'length', 'max'=>255),
            array('created_at, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, product_id, is_remote_file, mime_type, file_name, file_size, orig_name, created_at, created_by, modified_at, modified_by', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'product_id' => 'Идентификатор продукта из таблицы shop_product',
            'is_remote_file' => 'Is Remote File',
            'mime_type' => 'Mime-тип файла',
            'file_name' => 'Название файла',
            'file_size' => 'Размер в байтах',
            'orig_name' => 'Оригинальное название файла',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('product_id',$this->product_id);
        $criteria->compare('is_remote_file',$this->is_remote_file);
        $criteria->compare('mime_type',$this->mime_type,true);
        $criteria->compare('file_name',$this->file_name,true);
        $criteria->compare('file_size',$this->file_size);
        $criteria->compare('orig_name',$this->orig_name,true);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('modified_at',$this->modified_at,true);
        $criteria->compare('modified_by',$this->modified_by);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopProductsImages the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'ImageBehavior',
                'attributeName' => 'file_name',
                'savePathAlias' => 'upload/products_picture',
                'thumbnailPathAlias' => 'upload/products_picture/thumbs',
            ),
        );
    }
}
