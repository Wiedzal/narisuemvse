<?php

class ProductsController extends AdminModuleController
{
	public function init() 
    {
        $this->breadcrumbs = array(Yii::t('app', 'Товары') => $this->createUrl('index'));
        parent::init();
    }
    
    public function beforeRender($view) 
    {
        parent::beforeRender($view);
        
        if($view==='create' || $view==='edit')
        {
            Yii::app()->clientScript->scriptMap['bootstrap.min.js']  = false;
        }
        return true;
    }
    
    /*public function actions() 
    {
		return array(
            'upload' => array(
                'class' => 'xupload.actions.UploadAction', 
                'path' => Yii::app()->getBasePath().'/../upload/products_picture', 
                'publicPath' => Yii::app()->getBasePath().'/../upload/products_picture/thumbs',
                'thumbnailWidth' => 200,
                'thumbnailHeight' => 200, 
                'secureFileNames' => true,
            ), 
        );
	}*/

    public function actionIndex()
	{
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Управление товарами')));
        $this->pageTitle = Yii::t('app', 'Товары');

        $model = new ShopProducts('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ShopProducts'])) {
            $model->attributes = $_GET['ShopProducts'];
        }
        $this->render('index', array(
            'model' => $model,
        ));
	}
    
    public function actionCreate()
    {
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Создание товара')));
        $this->pageTitle = Yii::t('app', 'Создание товара');

        $model = new ShopProducts();

        if (!empty($_POST) && array_key_exists('ShopProducts', $_POST))
        {
            //var_dump($_POST);die;
            if(isset($_POST['ajax']) && $_POST['ajax']==='create-product-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            else
            {
                //var_dump($_POST);die;
                $model->attributes = $_POST['ShopProducts'];
                if ($model->validate() && $model->save())
                {
                    echo 'Заебись';
                    Yii::app()->end();
                }
                //die('2');
            }
        }
        
        //die;

        $this->render('create', array(
            'model' => $model,

        ));
    }
    
    public function actionEdit($id)
    {
        $this->pageTitle = Yii::t('app', 'Редактирование товара');

        $model = ShopProducts::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(404, Yii::t('app', 'Товар не найден'));
        
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Редактирование товара "' . $model->title . '"'));

        if (!empty($_POST) && array_key_exists('ShopProducts', $_POST))
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='create-product-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            else
            {
                $model->attributes = $_POST['ShopProducts'];
                if ($model->validate() && $model->save())
                {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Товар успешно отредактирован'));
                    Yii::app()->request->redirect($this->createUrl('index'));
                }
            }
        }

        $XUploadForm = new XUploadForm();
        
        $this->render('edit', array(
            'model' => $model,
            'XUploadForm' => $XUploadForm,
        ));
    }
   
    public function actionAjaxDeleteImage() 
    {
        if(Yii::app()->request->isAjaxRequest && isset($_POST))
        {
            $id = Yii::app()->request->getPost('id');
            
            $model = ShopProductsImages::model()->find('id=:id',array('id'=>$id));
            if($model == NULL)
            {
                echo CJSON::encode(array(
                    'status' => FALSE,
                    'message' => Yii::t('app', 'Не удается найти изображение. Обновите страницу и попробуйте ещё раз.')
                ));
                Yii::app()->end();
            }
            
            $model->delete();

            echo CJSON::encode(array('status' => TRUE));
            Yii::app()->end();
        }
    }
    
    public function actionUpload() 
    {
        //Here we define the paths where the files will be stored temporarily
        $path = Yii::getPathOfAlias('webroot').'/upload/products_picture/';
        $publicPath = Yii::getPathOfAlias('webroot').'/upload/products_picture/thumbs/';
        $thumbnailPath = '/upload/products_picture/thumbs/';
       
        if (!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        if (!is_dir($publicPath))
        {
            mkdir($publicPath, 0777, true);
        }
        //This is for IE which doens't handle 'Content-type: application/json' correctly
        header('Vary: Accept');
        if(isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
        {
            header('Content-type: application/json');
        } else 
        {
            header('Content-type: text/plain');
        }
     
        //Here we check if we are deleting and uploaded file
        if(isset($_GET["_method"])) 
        {
            if($_GET["_method"] == "delete")
            {
                if($_GET["file"][0] !== '.')
                {
                    $shopProductsImages = ShopProductsImages::model()->find('file_name=:file_name',array('file_name'=>$_GET["file"]));
                    if(!$shopProductsImages->delete())
                        throw new CHttpException(500, "Ошибка при удалении данных");
                        
                    $file = $path.$_GET["file"];
                    $thumb = $publicPath.$_GET["file"];
                    if(is_file($file)) 
                    {
                        unlink($file);
                    }
                    if(is_file($thumb)) 
                    {
                        unlink($thumb);
                    }
                }
                echo json_encode(true);
            }
        } 
        else
        {
            if(!isset($_GET["id"])) 
            {
                throw new CHttpException(500, "Ошибка запроса. Обновите страницу и попробуйте ещё раз");
            }
            $model = new XUploadForm;
            
            $fileValidator = CValidator::createValidator('FileValidator', $model, 'file', 
                array(
                    'maxSize'=>2097152,
                    'types'=>'jpg,jpeg,png,gif,bmp',
                    'mimeTypes'=>'image/jpeg,image/png,image/gif,image/bmp',
                    'tooLarge'=>Yii::t('yii','Размер файла "{file}" слишком велик, он не должен превышать 2MB.'),
                ));
            $model->validatorList->add($fileValidator);
            
            $model->file = CUploadedFile::getInstance($model, 'file');
            //We check that the file was successfully uploaded
            if($model->file !== null) 
            {
                //Grab some data
                $model->mime_type = $model->file->getType();
                $model->size = $model->file->getSize();
                $model->name = $model->file->getName();
                //(optional) Generate a random name for our file
                $filename = md5(Yii::app()->user->id . microtime() . $model->name);
                $filename .= "." . $model->file->getExtensionName();
                if($model->validate())
                {
                    //Move our file to our temporary dir
                    $model->file->saveAs($path.$filename);
                    chmod($path.$filename, 0777);
                    //here you can also generate the image versions you need 
                    //using something like PHPThumb
                    Yii::app()->simpleImage
                        ->load($path.$filename)
                        ->thumbnail(200,200)->save($publicPath.$filename);

                    $shopProductsImages = new ShopProductsImages();
                    $shopProductsImages->product_id = $_GET["id"];
                    $shopProductsImages->mime_type = $model->mime_type;
                    $shopProductsImages->file_name = $filename;
                    $shopProductsImages->file_size = $model->size;
                    $shopProductsImages->orig_name = $model->name;
                    
                    if(!$shopProductsImages->save())
                        throw new CHttpException( 500, "Ошибка при сохранении данных");
                    
                    //Now we need to save this path to the user's session
                    //Использовать, если изображения должны быть сохранены ПОСЛЕ сабмита формы
                    /*if(Yii::app()->user->hasState('images'))
                    {
                        $userImages = Yii::app()->user->getState('images');
                    }
                    else
                    {
                        $userImages = array();
                    }
                     $userImages[] = array(
                        "path" => $path.$filename,
                        //the same file or a thumb version that you generated
                        "thumb" => $path.$filename,
                        "filename" => $filename,
                        'size' => $model->size,
                        'mime' => $model->mime_type,
                        'name' => $model->name,
                    );
                    Yii::app()->user->setState('images', $userImages);*/
     
                    //Now we need to tell our widget that the upload was succesfull
                    //We do so, using the json structure defined in
                    // https://github.com/blueimp/jQuery-File-Upload/wiki/Setup
                    echo json_encode(array(array(
                            "name" => $model->name,
                            "type" => $model->mime_type,
                            "size" => $model->size,
                            "url" => $publicPath.$filename,
                            "thumbnail_url" => $thumbnailPath."$filename",
                            "delete_url" => $this->createUrl("upload", array(
                                "_method" => "delete",
                                "file" => $filename
                            )),
                            "delete_type" => "POST"
                        )));
                }
                else
                {
                    //If the upload failed for some reason we log some data and let the widget know
                    echo json_encode(array( 
                        array( "error" => $model->getErrors('file'),
                    )));
                    Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors()),
                        CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction" 
                    );
                }
            } 
            else
            {
                throw new CHttpException(500, "Не удалось загрузить файл");
            }
        }
    }
    
    
}