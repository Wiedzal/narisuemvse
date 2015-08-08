<?php

class ImageBehavior extends CActiveRecordBehavior
{
    /**
     * @var object Экземпляр класса SimpleImage для работы с изображениями 
     */
    public $simpleImage;
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
     * @var string расширения файлов, которые можно загружать (нужно для валидации)
     */
    public $fileTypes = 'jpg,jpeg,png,gif,bmp';
    /**
     * @var string mime-типы файлов, которые можно загружать (нужно для валидации)
     */
    public $mimeTypes = 'image/jpg,image/jpeg,image/png,image/gif,image/bmp';
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
 
        $this->simpleImage = Yii::app()->simpleImage;
        
        if (in_array($owner->scenario, $this->scenarios))
        {
            $fileValidator = CValidator::createValidator('FileValidator', $owner, $this->attributeName, 
                array(
                    'safe'=>false,
                    'enableClientValidation'=>true,
                    'allowEmpty'=>true,
                    'maxSize'=>$this->maxSize,
                    'types'=>$this->fileTypes,
                    'mimeTypes'=>$this->mimeTypes,
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

            $this->createStorageIfNotExists();

            $file->saveAs($this->savePathAlias . '/' . $fileSaveName);

            $this->createThumbnailForImage($fileSaveName);

            if ($this->owner->scenario == 'update')
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
        if ($this->checkFileExists($this->savePathAlias . '/' . $this->owner->{$this->attributeName}))
        {
            unlink($this->savePathAlias . '/' . $this->owner->{$this->attributeName});
        }

        if ($this->checkFileExists($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName}))
        {
            unlink($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName});
        }
    }
    
    /**
     * Загрузка изображения с удаленного сервера.
     * Валидация проходит в соответствии с настройками поведения
     * Метод сохраняет файл и сгенерированное превью в указанные при подключении поведения директории
     * Возвращает массив данных о загруженном изображении
     */
    public function uploadImageFromUrl($url)
    {
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        //var_dump($response);//die;
        if(!$response)
        {
            return array('error'=>"Не удается получить указанный файл.");
        }
        $headers = $this->getHeadersFromCurlResponse($response);
        //var_dump($headers);die;
        if (strpos($this->mimeTypes, $headers['Content-Type'])===FALSE)
        {
            return array('error'=>"Файл типа ".$headers['Content-Type']." не может быть загружен. Допустимые типы: ".$this->mimeTypes);
        }
        if ($headers['Content-Length'] > $this->maxSize)
        {
            return array('error'=>"Размер файла слишком велик, он не должен превышать 5Mb");
        }
        
        $fileOriginalName = basename($url);
        $fileExtension = pathinfo($url, PATHINFO_EXTENSION);
        $fileSaveName = md5(uniqid('',true)) . '.' . $fileExtension;
        
        $this->createStorageIfNotExists();
        
        file_put_contents($this->savePathAlias . '/' . $fileSaveName, file_get_contents($url));
        
        $this->createThumbnailForImage($fileSaveName);
        
        return array(
            'file_name' => $fileSaveName,
            'orig_name' => $fileOriginalName,
            'file_size' => $headers['Content-Length'],
            'mime_type' => $headers['Content-Type'],
            'is_remote_file' => (int)TRUE,
        );
    }

    /**
     * Возвращает заголовки curl-ответа в виде ассоциативного массива
     */
    public function getHeadersFromCurlResponse($response)
    {
        $headers = array();
        $string = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $string) as $i => $line)
        {
            if ($i === 0)
            {
                $headers['http_code'] = $line;
            }
            else
            {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
    
    /**
     * Получение источника (src для <img>) полноразмерного изображения
     */
    public function getImageUrl()
    {
        if ($this->checkFileExists($this->savePathAlias . '/' . $this->owner->{$this->attributeName}))
            return $this->getBaseImagePath() . '/' . $this->owner->{$this->attributeName}; 
        
        return false;
    }
    
    /**
     * Получение источника (src для <img>) превью изображения
     */
    public function getImageThumbUrl()
    {
        if ($this->checkFileExists($this->thumbnailPathAlias . '/' . $this->owner->{$this->attributeName}))
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
        if (file_exists($file) && is_file($file))
        {
            return true;
        }
        return false;
    } 
    
    /**
     * Проверка существования папки для сохранения изображений и её создание в случае отсутствия
     */
    public function createStorageIfNotExists()
    {
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
    }
    
    /**
     * Генерация превью для файла
     */
    public function createThumbnailForImage($fileSaveName)
    {
        $this->simpleImage
            ->load($this->savePathAlias . '/' . $fileSaveName)
            ->thumbnail($this->thumbnailWidth, $this->thumbnailHeight)->save($this->thumbnailPathAlias . '/' . $fileSaveName);
    } 
}