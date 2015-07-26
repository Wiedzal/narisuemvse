<?php

class ImageBehavior extends CActiveRecordBehavior
{
    /**
     * @var string название атрибута, хранящего в себе имя файла и файл
     */
    public $attributeName = 'image';
    /**
     * @var string алиас директории, куда будем сохранять файлы
     */
    public $savePathAlias = 'webroot.upload';
    /**
     * @var string алиас поддиректории, куда будем сохранять превью
     */
    public $thumbnailPathAlias = 'webroot.upload.thumbs';
    /**
     * @var int ширина создаваемого превью 
     */
    public $thumbnailWidth = 400;
    /**
     * @var int высота создаваемого превью 
     */
    public $thumbnailHeight = 300;
    /**
     * @var array сценарии валидации к которым будут добавлены правила валидации
     * загрузки файлов
     */
    public $scenarios = array('insert','update');
    /**
     * @var string типы файлов, которые можно загружать (нужно для валидации)
     */
    public $fileTypes = 'jpg,jpeg,png,gif,bmp';
    /**
     * @var int максимальный размер файла (нужно для валидации)
     */
    public $maxSize =  5242880;//2097152
    
    
    /**
     * Создание валидатора для загружаемого изображения
     */
    public function attach($owner)
    {
        parent::attach($owner);
 
        if(in_array($owner->scenario, $this->scenarios))
        {
            $fileValidator = CValidator::createValidator('FileValidator', $owner, $this->attributeName, 
                array(
                    'safe'=>false,
                    'enableClientValidation'=>true,
                    'allowEmpty'=>true,
                    'maxSize'=>$this->maxSize,
                    'types'=>$this->fileTypes,
                    'mimeTypes'=>'image/jpeg,image/png,image/gif,image/bmp',
                    'tooLarge'=>Yii::t('yii','Размер файла "{file}" слишком велик, он не должен превышать {limit}.'),
                ));
            $owner->validatorList->add($fileValidator);
        }
    }

    /**
     * Сохранение изображения на сервере с созданием превью
     */
    public function beforeSave($event)
    {
        if ($file = CUploadedFile::getInstance($this->owner, $this->attributeName))
        {
            $fileSaveName = md5(uniqid('',true)) . '.' . $file->extensionName;
            
            $directory = Yii::getPathOfAlias('webroot') . '/' . $this->savePathAlias . '/';
            if (!is_dir($directory))
            {
                mkdir($directory, 0777, true);
            }
            $subDirectory = Yii::getPathOfAlias('webroot') . '/' . $this->thumbnailPathAlias . '/';
            if (!is_dir($subDirectory))
            {
                mkdir($subDirectory, 0777, true);
            }

            $file->saveAs($this->savePathAlias . '/' . $fileSaveName);

            Yii::app()->simpleImage
                ->load($this->savePathAlias . '/' . $fileSaveName)
                ->thumbnail($this->thumbnailWidth, $this->thumbnailHeight)->save($this->thumbnailPathAlias . '/' . $fileSaveName);

            if($this->owner->scenario == 'update')
            {
                $this->deleteFile();
            }
            
            $this->owner->{$this->attributeName} = $fileSaveName;
        }
    }
    
    public function beforeDelete($event)
    {
        $this->deleteFile();
    }

    /**
     * Удаление файла изображения и его превью
     */
    public function deleteFile()
    {
        if($this->checkFileExists($this->savePathAlias . '/' . $this->owner->{$this->attributeName}))
        {
            unlink($this->savePathAlias . '/' . $this->owner->{$this->attributeName});
        }

        if($this->checkFileExists($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName}))
        {
            unlink($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName});
        }
    }
    
    /**
     * Получение источника (src для <img>) полноразмерного изображения
     */
    public function getImageUrl()
    {
        if($this->checkFileExists($this->savePathAlias . '/' . $this->owner->{$this->attributeName}))
            return $this->getBaseImagePath() . '/' . $this->owner->{$this->attributeName}; 
        
        return false;
    }
    
    /**
     * Получение источника (src для <img>) превью изображения
     */
    public function getImageThumbUrl()
    {
        if($this->checkFileExists($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName}))
            return $this->getBaseThumbnailPath() . $this->owner->{$this->attributeName};
        
        return false;
    }
    
    /**
     * Получение пути к директории с полноразмерным изображением
     */
    private function getBaseImagePath()
    {
        return Yii::app()->request->hostInfo . '/' . $this->savePathAlias . '/';
    } 
    
    /**
     * Получение пути к директории с превью изображения
     */
    private function getBaseThumbnailPath()
    {
        return  Yii::app()->request->hostInfo . '/' . $this->thumbnailPathAlias . '/';
    } 
    
    /**
     * Проверка существования файла
     */
    private function checkFileExists($file)
    {
        if(file_exists($file) && is_file($file))
        {
            return true;
        }
        return false;
    } 
}