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
    const IS_SHOW_ACTIVE = 1;
    const IS_SHOW_INACTIVE = 0;
    
    /**
     * Атрибуты для фильтрации в CGridView
     */
    public $_price_min;
    public $_price_max;
    
    public $_remoteFile;
    /**
     * Атрибут, в котором хранится имя файла главного изображения
     */
    public $_mainImageFileName;
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
            array('_remoteFile', 'length', 'max'=>255, 'on'=>'import'),
            array('_remoteFile', 'url', 'on'=>'import'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, article, alias, title, category_id, description, price, is_available, main_image_id, is_show, meta_keywords, meta_description, created_at, created_by, modified_at, modified_by, _price_min, _price_max', 'safe', 'on'=>'search,import'),
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
            'is_available' => 'Доступность/есть ли на складе',
            'main_image_id' => 'Основное изображение',
            'is_show' => 'Отображение',
            'meta_keywords' => 'Ключевые слова (для поисковых систем)',
            'meta_description' => 'Описание (для поисковых систем)',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            
            '_mainImageFileName' => 'Имя файла основного изображения',
            '_remoteFile' => 'Ссылка на основное изображение',
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

        /*if((isset($this->_price_min) && trim($this->_price_min) != "") && (isset($this->_price_max) && trim($this->_price_max) != ""))
        {
            $criteria->addBetweenCondition('t.price', $this->_price_min, $this->_price_max);
        }
		if((isset($this->_price_min) && trim($this->_price_min) != ""))
        {
        
            $criteria->addCondition('t.price >= :price_min', 'AND');
            $criteria->params += array(':price_min'=>$this->_price_min);
        }        
        if((isset($this->_price_max) && trim($this->_price_max) != ""))
        {
            $criteria->addCondition('t.price <= :price_max', 'AND');
            $criteria->params += array(':price_max'=>$this->_price_max);
        }*/

        $criteria->compare('id',$this->id);
        $criteria->compare('article',$this->article,true);
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('price',$this->price);
        $criteria->compare('is_available',$this->is_available);
        $criteria->compare('main_image_id',$this->main_image_id);
        $criteria->compare('is_show',$this->is_show);
        $criteria->compare('meta_keywords',$this->meta_keywords,true);
        $criteria->compare('meta_description',$this->meta_description,true);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('modified_at',$this->modified_at,true);
        $criteria->compare('modified_by',$this->modified_by);

        //$criteria->order = 'id DESC';

        $CActiveDataProvider = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array('attributes'=>array('id','article','title','price')),
            'pagination'=>array(
                'pageSize'=> 2,
            ),
        ));
        return $CActiveDataProvider;
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
        $this->_mainImageFileName = $this->main_image ? $this->main_image->file_name : null;
    }

    /*public function beforeSave()
    {
        parent::beforeSave();
        return true;
    }*/
    
    public function afterDelete()
    {
        parent::afterDelete();
        foreach($this->images as $image)
        {
            $image->delete();
        }
    }
    
    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'ImageBehavior',
                'attributeName' => '_mainImageFileName',
                'savePathAlias' => 'upload/products_picture',
                'thumbnailPathAlias' => 'upload/products_picture/thumbs',
            ),
        );
    }

    public static function gridIsShowItem($value)
    {
        $items = self::gridIsShowItems();
        return (array_key_exists($value, $items)) ? $items[$value] : 'н/д';
    }
    
    public static function gridIsShowItems()
    {
        return array(
            self::IS_SHOW_ACTIVE => Yii::t('app', 'Да'),
            self::IS_SHOW_INACTIVE => Yii::t('app', 'Нет'),
        );
    }
    
    /**
     * Возвращает массив доступных для импорта полей товара в виде column => label
     */
    public static function getAvailableColumns()
    {
        $tech_columns = array(
            'id'=>'id',
            'category_id'=>'category_id',
            'main_image_id'=>'main_image_id',
            'created_at'=>'created_at',
            'created_by'=>'created_by',
            'modified_at'=>'modified_at',
            'modified_by'=>'modified_by',
            '_mainImageFileName'=>'_mainImageFileName',
        );
        $columns = array_diff_key(self::model()->attributeLabels(), $tech_columns);
        return $columns;
    }
}
