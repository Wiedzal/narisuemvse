<?php

/**
 * RemoteFileForm class.
 * RemoteFileForm is the data structure for keeping
 */
class RemoteFileForm extends CFormModel
{
    public $remotefile;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('remotefile', 'url'),
            array('remotefile', 'length', 'max'=>255),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'remotefile' => Yii::t('app', 'Ссылка на изображение'),
        );
    }
}
