<?php

/**
 * This is the model class for table "shop_categories".
 *
 * The followings are the available columns in table 'shop_categories':
 * @property integer $id
 * @property string $alias
 * @property integer $parent_id
 * @property integer $upline
 * @property integer $tree_level
 * @property integer $is_content_page
 * @property string $title
 * @property string $description
 * @property integer $is_show
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_descripion
 * @property string $created_at
 * @property integer $created_by
 * @property string $modified_at
 * @property integer $modified_by
 */
class ShopCategories extends ActiveRecord
{
    const CATEGORY_ROOT_ID = 1;

    protected $_url;
    protected $_urlPrefix = 'shop/';
    protected $_parentIdOld;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_categories';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('alias, title, parent_id', 'required'),
            array('parent_id, tree_level, is_content_page, is_show, created_by, modified_by', 'numerical', 'integerOnly'=>true),
            array('alias', 'match', 'pattern'=>'/^[a-z\d\/-]{1,}$/i', 'message'=>Yii::t('app', 'Псевдоним содержит недопустимые символы')),
            array('alias', 'unique', 'message' => 'К сожалению, такой псевдоним занят. Выберите другой.'),
            array('alias, upline, title, meta_keywords', 'length', 'max'=>255),
            array('meta_description', 'length', 'max'=>1000),
            array('description, created_at, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, alias, parent_id, upline, tree_level, is_content_page, title, description, is_show, image, meta_keywords, meta_description, created_at, created_by, modified_at, modified_by', 'safe', 'on'=>'search'),
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
            'parent' => array(self::BELONGS_TO, 'ShopCategories', 'parent_id'),
            'children' => array(self::HAS_MANY, 'ShopCategories', 'parent_id', 'order'=>'upline'),
            'childrenCount' => array(self::STAT, 'ShopCategories', 'parent_id'),
            'products' => array(self::MANY_MANY, 'ShopProducts', 'category_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'alias' => 'Псевдоним',
            'parent_id' => 'Идентификатор родительской категории',
            'upline' => 'Цепочка вышестоящих категорий',
            'tree_level' => 'Уровень вложенности',
            'is_content_page' => 'Тип отображения содержимого категории',
            'title' => 'Название категории',
            'description' => 'Описание категории',
            'is_show' => 'Отображать на сайте',
            'image' => 'Изображение',
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
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('parent_id',$this->parent_id);
        $criteria->compare('upline',$this->upline);
        $criteria->compare('tree_level',$this->tree_level);
        $criteria->compare('is_content_page',$this->is_content_page);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('is_show',$this->is_show);
        $criteria->compare('image',$this->image,true);
        $criteria->compare('meta_keywords',$this->meta_keywords,true);
        $criteria->compare('meta_description',$this->meta_description,true);
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
     * @return ShopCategories the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Текущий либо старый(для перемещаемой категории) родитель
     */
    public function afterFind()
    {
        $this->_parentIdOld = $this->parent_id;
    }

    /*public function beforeSave()
    {
    }*/
    
    public function afterSave()
    {
        if ($this->isNewRecord == true) // Yii сохраняет статус isNewRecord в true даже после добавления записи в таблицу
        {
            $this->isNewRecord = false;
            $this->saveAttributes(array(
                'upline' => $this->parent->upline.'.'.$this->id,
                'tree_level' => $this->parent->tree_level + 1
            ));
            return;
        }
        $this->updatePathData();
    }
    
    /**
     * Подключение поведения для загрузки изображений
     */
    public function behaviors()
    {
        return array(
            'imageBehavior' => array(
                'class' => 'ImageBehavior',
                'savePathAlias' => 'upload/categories_picture',
                'thumbnailPathAlias' => 'upload/categories_picture/thumbs',
            ),
        );
    }
    
    /**
     * Получаем ассоциативный массив возможных родителей вида ($id=>$title, $id=>$title, ...) для выпадающих списков
     * Получаем массив опций для оформления отступов.
     * @return object
     */
    public function getPossibleParents()
    {
        $criteria = new CDbCriteria();

        if (!$this->isNewRecord)
        {
            $criteria->addCondition('upline NOT LIKE :currentUpline');
            $criteria->addCondition('id != :currentId', 'AND');

            $criteria->params = array(
                ':currentId' => $this->getPrimaryKey(),
                ':currentUpline' => $this->upline.'%'
            );
        }

        $criteria->order = 'upline';
        
        $models = self::model()->findAll($criteria);
        
        $listOptions = array();
        foreach ($models as $model)
        {
            $listOptions[$model->id] = array('style' => 'padding-left: '.($model->tree_level * 5).'px');
        }
        
        return (object) array(
            'list' => CHtml::listData($models, 'id', 'title'),
            'listOptions' => $listOptions
        );
    }
    
    /**
     * Получаем ассоциативный массив категорий вида ($id=>$title, $id=>$title, ...) для выпадающих списков при создании продукта
     * Получаем массив опций для оформления отступов.
     * @return object
     */
    public static function getAssocList()
    {
        $criteria = new CDbCriteria();
        
        $criteria->addCondition('upline LIKE :upline');
        $criteria->params = array(':upline' => self::CATEGORY_ROOT_ID . '%');
        $criteria->order = 'upline';
        
        $models = self::model()->findAll($criteria);
        
        $listOptions = array();
        foreach ($models as $model)
        {
            $listOptions[$model->id] = array('style' => 'padding-left: '.($model->tree_level * 5).'px');
        }
        
        return (object) array(
            'list' => CHtml::listData($models, 'id', 'title'),
            'listOptions' => $listOptions
        );
    }
    
    /**
     * Поиск категории по псевдониму
     * @return ActiveRecord model
     */
    public function findByPath($path)
    {
        $domens = explode('/', trim($path, '/'));
        $model = null;
        
        $criteria = new CDbCriteria();
        if (count($domens)==1)
        {
            $criteria->condition = 'alias=:alias AND parent_id=:root_id';
            $criteria->params = array(':alias'=>$domens[0],':root_id'=>self::CATEGORY_ROOT_ID);
            $model = $this->find($criteria);
        }
        else 
        {
            $criteria->condition = 'alias=:alias';
            $criteria->params = array(':alias'=>$domens[0]);
            
            $parent = $this->find($criteria);
            if ($parent)
            {
                $domens = array_slice($domens, 1);
                foreach ($domens as $alias){
                    $model = $parent->getChildByAlias($alias);
                    if (!$model) 
                        return null;
                    $parent = $model;
                }
            }
        }
        
        return $model;
    }
    
    /**
     * Поиск потомка по псевдониму
     * @return ActiveRecord model
     */
    protected function getChildByAlias($alias)
    {
        $criteria = new CDbCriteria();
        $criteria->mergeWith(array(
            'condition'=>'t.alias=:alias AND t.parent_id=:parent_id',
            'params'=>array(
                ':alias'=>$alias,
                ':parent_id'=>$this->getPrimaryKey()
            )
        ));
        return $this->find($criteria);
    }

    
    /**
     * Получение всех потомков по id категории 
     */
    public function getFullBranch($id = self::CATEGORY_ROOT_ID)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'upline LIKE :upline';
        $criteria->params = array('upline'=> $id.'.%');
        $criteria->order = 'upline';
        
        return self::model()->findAll($criteria);
    }
    
    /**
     * Получение массива идентификаторов потомков текущей категории
     */
    public function getChildsArray()
    {
        $childs = array();
        
        $criteria = new CDbCriteria;
        $criteria->select = 'id';
        $criteria->condition = 'upline LIKE :upline';
        $criteria->params = array('upline'=> $this->id.'.%');

        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
        $command = $builder->createFindCommand('shop_categories', $criteria);
        $parents = $command->queryAll();
        
        $childs = array_map(function($element){return $element['id'];}, $parents);
        return $childs;
    }
    
    /**
     * Получение url-а категории 
     */
    public function getUrl()
    {
        if ($this->_url === null)
        {
            $this->_url = Yii::app()->request->baseUrl . '/' . $this->_urlPrefix . $this->getPath() . Yii::app()->urlManager->urlSuffix;
        }
        return $this->_url;
    }

    /**
     * Собирает полный адресный путь для текущей категории
     * @param string $separator
     * @return string
     */
    public function getPath($separator='/')
    {
        $uri = array($this->alias);
        $category = $this;
        $i = 10;

        while ($i-- && ($category->parent_id > self::CATEGORY_ROOT_ID))
        {
            $uri[] = $category->parent->alias;
            $category = $category->parent;
        }

        return implode(array_reverse($uri), $separator);
    }
    /**
     * Подготовливает массив хлебных крошек для zii.widgets.CBreadcrumbs
     * @param bool $lastLink отображение последнего элемента (true - активная ссылка)
     * @return array
     */
    public function getBreadcrumbs($lastLink=false)
    {
        if ($lastLink)
        {
            $breadcrumbs = array($this->title => $this->url);
        }
        else
        {
            $breadcrumbs = array($this->title);
        }
        
        $page = $this;
        $i = 50;
        while ($i-- && ($page->parent_id > self::CATEGORY_ROOT_ID))
        {
            $breadcrumbs[$page->parent->title] = $page->parent->url;
            $page = $page->parent;
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Получает массив с типами отображений категории. Используется для построения радиобаттона поля is_content_page
     * @return array
     */
    public function getMappingList()
    {
        return array(
            '0'=>Yii::t('app', 'Товары подкатегорий'), 
            '1'=>Yii::t('app', 'Контентную страницу'),
        );
    }
    
    /**
     * START
     * Группа методов для перемещения категории
     */
    public function updatePathData()
    { 
        $this->updateChildrenPathData();
        
        $upline = $this->callStoredFunction('sf_get_category_upline', $this->id);
        $tree_level = (int)$this->callStoredFunction('sf_get_category_level', $this->id);

        $this->saveAttributes(array(
            'upline' => $upline,
            'tree_level' => $tree_level
        ));
    }
    
    /**
     * Обновление пути и вложенности потомков
     */
    protected  function updateChildrenPathData()
    {
        if ($this->parent_id == $this->_parentIdOld)
            return;

        $children = $this->getUplineChildren();

        foreach ($children as $child)
        {
            $child->updatePathData();
        }
    }
    
    /**
     * Получение всех потомков категории по upline
     */
    protected function getUplineChildren()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('upline LIKE :currentUpline');
        $criteria->addCondition('id != :currentId', 'AND');
        $criteria->order = 'upline';
        $params = array(
            ':currentUpline' => $this->upline.'%',
            ':currentId' => $this->getPrimaryKey()
        );

        $criteria->params = $params;

        return self::model()->findAll($criteria);
    }

    /**
     * Метод вызова хранимых функций
     */
    protected function callStoredFunction($function_name, $param)
    {
        $function_name = addslashes($function_name);
        $param = addslashes($param);

        $command = Yii::app()->db->createCommand()->setText("SELECT $function_name($param);");
        $result = $command->queryRow();
        return current($result);
    }
    /**
     * FINISH
     * Группа методов для перемещения категории
     */
}
