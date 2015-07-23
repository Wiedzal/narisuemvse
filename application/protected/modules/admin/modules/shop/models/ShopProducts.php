<?php

/**
 * This is the model class for table "shop_products".
 *
 * The followings are the available columns in table 'shop_products':
 * @property integer $id
 * @property string $article
 * @property string $alias
 * @property string $title
 * @property integer $category_id
 * @property string $description
 * @property string $price
 * @property integer $is_available
 * @property integer $main_image_id
 * @property integer $is_show
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 */
class ShopProducts extends ActiveRecord
{
    public $mainImageFileName;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_products';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('article, alias, title, category_id, price', 'required'),
            array('price', 'match', 'pattern'=>'/^[0-9]{1,18}([\.,][0-9]{0,2})?$/', 'message'=>Yii::t('app', 'Поле «{attribute}» содержит недопустимые символы')),
            array('alias', 'match', 'pattern'=>'/^[a-z\d\/-]{1,}$/i', 'message'=>Yii::t('app', 'Поле «{attribute}» содержит недопустимые символы')),
            array('alias', 'unique', 'message' => 'К сожалению, такой псевдоним занят. Выберите другой.'),
            array('article', 'unique', 'message' => 'К сожалению, такой артикул занят. Выберите другой.'),
            array('category_id, is_available, main_image_id, is_show, created_by, modified_by', 'numerical', 'integerOnly'=>true),
            array('article, alias, title, meta_keywords', 'length', 'max'=>255),
            array('price', 'length', 'max'=>20),
            array('meta_description', 'length', 'max'=>1000),
            array('description, created_at, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, article, alias, title, category_id, description, price, is_available, main_image_id, is_show, meta_keywords, meta_description, created_at, created_by, modified_at, modified_by', 'safe', 'on'=>'search'),
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
            'category' => array(self::HAS_ONE, 'ShopCategories', array('id'=>'category_id')),
            'images' => array(self::HAS_MANY, 'ShopProductsImages', 'product_id'),
            'main_image' => array(self::HAS_ONE, 'ShopProductsImages', array('id'=>'main_image_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'article' => 'Артикул',
            'alias' => 'Псевдоним',
            'title' => 'Название товара',
            'category_id' => 'Категория',
            'description' => 'Описание товара',
            'price' => 'Цена',
            'is_available' => 'Доступность/есть ли на складе)',
            'main_image_id' => 'Идентификатор главного изображения из таблицы shop_products_images ',
            'is_show' => 'Отображение',
            'meta_keywords' => 'Ключевые слова (для поисковых систем)',
            'meta_description' => 'Описание (для поисковых систем)',
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
        $criteria->compare('article',$this->article,true);
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('is_available',$this->is_available);
        $criteria->compare('main_image_id',$this->main_image_id);
        $criteria->compare('is_show',$this->is_show);
        $criteria->compare('meta_keywords',$this->meta_keywords,true);
        $criteria->compare('meta_description',$this->meta_description,true);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('modified_at',$this->modified_at,true);
        $criteria->compare('modified_by',$this->modified_by);

        $criteria->order = 'id DESC';
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
				'pageSize'=> 5,
			),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopProducts the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function afterFind()
    {
        parent::afterFind();
        $this->mainImageFileName = $this->main_image ? $this->main_image->file_name : null;
    }
    
    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'ImageBehavior',
                'attributeName' => 'mainImageFileName',
                'savePathAlias' => 'upload/products_picture',
                'thumbnailPathAlias' => 'upload/products_picture/thumbs',
            ),
        );
    }
}
