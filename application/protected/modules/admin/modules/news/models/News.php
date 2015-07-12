<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 * @property string $brief_text
 * @property string $text
 * @property string $source
 * @property string $source_title
 * @property integer $is_show
 * @property string $image_name
 * @property string $created_at
 * @property integer $created_by
 */
class News extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, brief_text, text', 'required'),
            array('is_show, created_by', 'numerical', 'integerOnly'=>true),
            array('title, brief_text, source_title', 'length', 'max'=>255),
            array('source', 'length', 'max'=>1000),
            array('created_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, image, title, brief_text, text, source, source_title, is_show, created_at, created_by', 'safe', 'on'=>'search'),
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
            //'image' => array(self::HAS_ONE, 'Images', array('id' => 'image_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'image' => 'Изображение',
            'title' => 'Заголовок',
            'brief_text' => 'Краткое описание',
            'text' => 'Полный текст',
            'source' => 'Источник ',
            'source_title' => 'Ссылка на источник',
            'is_show' => 'Отображать на сайте ',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
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
        $criteria->compare('image',$this->image);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('brief_text',$this->brief_text,true);
        $criteria->compare('text',$this->text,true);
        $criteria->compare('source',$this->source,true);
        $criteria->compare('source_title',$this->source_title,true);
        $criteria->compare('is_show',$this->is_show);
        $criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('created_by',$this->created_by);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return News the static model class
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
                'savePathAlias' => 'upload/news_picture',
                'thumbnailPathAlias' => 'upload/news_picture/thumbs',
            ),
        );
    }
}
