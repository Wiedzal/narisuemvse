<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property integer $id
 * @property string $object_alias
 * @property string $file_type
 * @property string $relative_path
 * @property string $file_path
 * @property string $full_path
 * @property string $file_name
 * @property string $orig_name
 * @property double $file_size
 * @property string $created_at
 * @property integer $created_by
 */
class Images extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object_alias', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('file_size', 'numerical'),
			array('object_alias, file_type', 'length', 'max'=>100),
			array('relative_path, file_path, full_path, file_name, orig_name', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object_alias, file_type, relative_path, file_path, full_path, file_name, orig_name, file_size, created_at, created_by', 'safe', 'on'=>'search'),
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
			'object_alias' => 'Object Alias',
			'file_type' => 'File Type',
			'relative_path' => 'Relative Path',
			'file_path' => 'File Path',
			'full_path' => 'Full Path',
			'file_name' => 'File Name',
			'orig_name' => 'Orig Name',
			'file_size' => 'File Size',
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
		$criteria->compare('object_alias',$this->object_alias,true);
		$criteria->compare('file_type',$this->file_type,true);
		$criteria->compare('relative_path',$this->relative_path,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('full_path',$this->full_path,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('orig_name',$this->orig_name,true);
		$criteria->compare('file_size',$this->file_size);
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
	 * @return Images the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
