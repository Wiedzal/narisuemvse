<?php

class ActiveRecord extends CActiveRecord
{
    public function __construct($scenario = 'insert')
    {
        parent::__construct($scenario);
    }
    
    /**
     * Сохранение технических полей с информацией о создании записи
     */
    public function beforeSave()
    {
        if ($this->isNewRecord)
        {
            $this->created_at = new CDbExpression('NOW()');
            if ((Yii::app()->user == NULL) || (Yii::app()->user->id == null))
                $this->created_by = 0;
            else
                $this->created_by = Yii::app()->user->id;
        }
        else
        {
            $this->modified_at = new CDbExpression('NOW()');
            if ((Yii::app()->user == NULL) || (Yii::app()->user->id == null)) 
                $this->modified_by = 0;
            else 
                $this->modified_by = Yii::app()->user->id;
        }

        return parent::beforeSave();
    }
}