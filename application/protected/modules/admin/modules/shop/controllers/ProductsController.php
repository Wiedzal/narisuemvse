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

    public function actionIndex()
    {
        $this->pageTitle = Yii::t('app', 'Товары');
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Управление товарами')));

        $model = new ShopProducts('search');
        $model->unsetAttributes();

        if (isset($_GET['ShopProducts'])) 
        {
            $model->attributes = $_GET['ShopProducts'];
        }

        if (isset($_GET['ajax'])) 
        {
            $this->renderPartial('_grid', array('model' => $model));
        }
        else 
        {
            $this->render('index', array('model' => $model));
        }
    }
    
    public function actionCreate()
    {
        $this->pageTitle = Yii::t('app', 'Создание товара');
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Создание товара')));

        $model = new ShopProducts();

        if (Yii::app()->request->isAjaxRequest && isset($_POST['ShopProducts']))
        {
            $params = $_POST['ShopProducts'];
            
            if($params['id'])
                $model = ShopProducts::model()->findByPk($params['id']);
                
            if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            else
            {
                $model->attributes = $_POST['ShopProducts'];
                $model->save();
                echo CJSON::encode(array('id' => $model->id));
                Yii::app()->end();
            }
        }
        
        $XUploadForm = new XUploadForm();
        $RemoteFileForm = new RemoteFileForm();
        
        $this->render('create', array(
            'model' => $model,
            'XUploadForm' => $XUploadForm,
            'RemoteFileForm' => $RemoteFileForm,
        ));
    }
    
    public function actionEdit($id)
    {
        $model = ShopProducts::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(404, Yii::t('app', 'Товар не найден'));

        $this->pageTitle = 'Редактирование товара "' . $model->title . '"';
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Редактирование товара "' . $model->title . '"'));

        if (isset($_POST['ShopProducts']))
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            else
            {
                $model->attributes = $_POST['ShopProducts'];
                $model->save();
                echo CJSON::encode(array('id' => $model->id));
                Yii::app()->end();
            }
        }

        $XUploadForm = new XUploadForm();
        $RemoteFileForm = new RemoteFileForm();
        
        $this->render('edit', array(
            'model' => $model,
            'XUploadForm' => $XUploadForm,
            'RemoteFileForm' => $RemoteFileForm,
        ));
    }
    
    public function actionView($id)
    {
        $model = ShopProducts::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(404, Yii::t('app', 'Товар не найден'));

        $this->pageTitle = 'Просмотр товара "' . $model->title . '"';
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Просмотр товара "' . $model->title . '"'));

        $this->render('view', array('model' => $model));
    }
    
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            $model = ShopProducts::model()->findByPk($id);
            $model->delete();
     
            if(!isset($_POST['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionCsvdownload()
    {
        $model = new ShopProducts();
        $columns = $model->getAvailableColumns();
        $firstLine = array_values($columns);

        $file = fopen('upload/example.csv', 'w');
        fputcsv($file, $firstLine, ';');
        fclose($file);
        
        $csvHandle = file_get_contents('upload/example.csv');
        $csvHandle = iconv("UTF-8", "windows-1251", $csvHandle);
        file_put_contents('upload/example.csv', $csvHandle);

        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        header("Content-Disposition: attachment;filename=example.csv");
        header("Content-Transfer-Encoding: binary");

        readfile('upload/example.csv');

        Yii::app()->end();
    }
    
    public function actionImport()
    {
        $this->pageTitle = 'Импорт товаров';
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Импорт товаров'));

        $importCsvForm = new ImportCsvForm();
        $errors = array();

        if (isset($_POST['ImportCsvForm']))
        {
            $importCsvForm->attributes = $_POST['ImportCsvForm'];

            if($importCsvForm->validate())
            {
                $model = new ShopProducts();
                $columns = array_values(array_flip($model->getAvailableColumns()));
                //var_dump($columns);die;
                $csvFile = CUploadedFile::getInstance($importCsvForm, 'csvFile'); 
                $csvFileTemp = $csvFile->getTempName();

                $csvHandle = file_get_contents($csvFileTemp);
                $csvHandle = iconv("WINDOWS-1251", "UTF-8", $csvHandle);
                file_put_contents($csvFileTemp, $csvHandle);
                
                $csvHandle = fopen($csvFileTemp, "r");

                $checkedLine = fgetcsv($csvHandle, 0, $importCsvForm->delimiter);
                if(count($checkedLine) > count($columns))
                {
                    $importCsvForm->addError('csvFile', 'Неверная схема csv-документа');
                    $errors[] = $importCsvForm;
                    die('error');
                }
                
                rewind($csvHandle);

                while(($csvLine = fgetcsv($csvHandle, 0, $importCsvForm->delimiter)) !== FALSE)
                {
                    $i = 0;
                    foreach($csvLine as $key=>$value) 
                    {
                        $data[$columns[$i]] = $value;
                        $i++;
                    }
                    $csvDataArray[] = $data;
                }
                //var_dump($csvDataArray);die;
                if($importCsvForm->skipFirstLine)
                {
                    unset($csvDataArray[0]);
                }
                //var_dump($csvDataArray);die;
                foreach($csvDataArray as $attributes)
                {
                    $model = new ShopProducts('import');
                    $model->attributes = $attributes;
                    $model->category_id = $importCsvForm->categoryId;
                    //var_dump($model->attributes);die;
                    if(!$model->validate())
                    {
                        $errors[] = $model;
                    }
                    //var_dump($model->attributes);
                    //var_dump($model->_remoteFile);
                    //var_dump($model->getErrors());die;
                }
                
                $transaction = Yii::app()->db->beginTransaction();
                try 
                {
                    foreach($csvDataArray as $attributes)
                    {
                        $ShopProducts = new ShopProducts('import');
                        $ShopProducts->attributes = $attributes;
                        $ShopProducts->category_id = $importCsvForm->categoryId;
                        if(!$ShopProducts->save())
                        {
                            throw new Exception('Ошибка при сохранении данных.');
                        }
                        
                        $ShopProductsImages = new ShopProductsImages();
                        $ShopProductsImages->createStorageIfNotExists();
                
                        $attr = $ShopProductsImages->uploadImageFromUrl($attributes['_remoteFile']);
                        if(isset($attr['error']))
                        {
                            echo CJSON::encode(array('error'=>$attr['error']));
                            Yii::app()->end();
                        }
                        //$ShopProductsImages->validate();
                        //var_dump($ShopProductsImages->getErrors());die;
                        $ShopProductsImages->attributes = $attr;
                        $ShopProductsImages->product_id = $ShopProducts->id;
                       // var_dump($ShopProductsImages->save());die;
                        if(!$ShopProductsImages->save())
                        {
                            throw new Exception('Ошибка при сохранении данных.');
                        }
                    }
                    $transaction->commit();
                }
                catch(Exception $e) 
                {
                    $error = $e->getMessage();
                    $transaction->rollBack();
                }
                
                //var_dump($error);
                die;
                
                $this->redirect(array('import'));
            }
        }
        $this->render('import', array(
            'model' => $importCsvForm, 
            'errors' => $errors,
        ));
    }

    public function actionAjaxDeleteImage() 
    {
        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest)
        {
            $id = Yii::app()->request->getPost('id');
            
            $model = ShopProductsImages::model()->findByPk($id);
            if($model == NULL)
            {
                echo CJSON::encode(array(
                    'status' => FALSE,
                    'message' => Yii::t('app', 'Не удается найти изображение. Обновите страницу и попробуйте ещё раз.')
                ));
                Yii::app()->end();
            }
            
            $is_main = FALSE;
            
            if($model->owner->main_image_id == $model->id)
            {
                $is_main = TRUE;
                $model->owner->main_image_id = NULL;
                $model->owner->save();
            }
            
            $model->delete();

            echo CJSON::encode(array(
                'status' => TRUE,
                'is_main' => $is_main
            ));
            Yii::app()->end();
        }
    }
    
    public function actionAjaxUpdateMainImage() 
    {
        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest)
        {
            $id = Yii::app()->request->getPost('id');
            $model = ShopProductsImages::model()->findByPk($id);
            if(!$model->updateOwnerMainImage())
            {
                echo CJSON::encode(array(
                    'status' => FALSE,
                    'message' => Yii::t('app', 'Не удается выполнить операцию. Обновите страницу и попробуйте ещё раз.')
                ));
                Yii::app()->end();
            }
            echo CJSON::encode(array(
                'status' => TRUE,
                'source' => $model->imageThumbUrl
            ));
            Yii::app()->end();
        }
    }
    
    public function actionAjaxClearMainImage() 
    {
        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest)
        {
            $id = Yii::app()->request->getPost('id');
            $model = ShopProducts::model()->findByPk($id);
            if($model == NULL)
            {
                echo CJSON::encode(array(
                    'status' => FALSE,
                    'message' => Yii::t('app', 'Не удается выполнить операцию. Обновите страницу и попробуйте ещё раз.')
                ));
                Yii::app()->end();
            }
            
            $model->main_image_id = NULL;
            $model->save();
            
            echo CJSON::encode(array('status' => TRUE));
            Yii::app()->end();
        }
    }
    
    public function actionUploadFromUrl($id) 
    {
        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest)
        {
            $params = Yii::app()->request->getPost('RemoteFileForm');
            if(!$params)
            {
                echo CJSON::encode(array('error'=>Yii::t('app', 'Не удается выполнить операцию. Обновите страницу и попробуйте ещё раз.')));
                Yii::app()->end();
            }
            
            $product = ShopProducts::model()->findByPk($id);
            if(!$product)
            {
                echo CJSON::encode(array('error'=>Yii::t('app', 'Не удается найти товар.')));
                Yii::app()->end();
            }

            $model = new ShopProductsImages();
            
            $model->product_id = $id;
            $attributes = $model->uploadImageFromUrl($params['remotefile']);
            if(isset($attributes['error']))
            {
                echo CJSON::encode(array('error'=>$attributes['error']));
                Yii::app()->end();
            }
            //var_dump($attributes)die;
            $model->attributes = $attributes;
            if(!$model->save())
            {
                echo CJSON::encode(array('error'=>Yii::t('app', 'Ошибка при сохранении данных.')));
                Yii::app()->end();
            }
            echo CJSON::encode(array('error'=>false));
            Yii::app()->end();
        }
        echo CJSON::encode(array('error'=>Yii::t('app', 'Некорректный запрос.')));
        Yii::app()->end();
    }
    
    public function actionUpload() 
    {
        $model = new ShopProductsImages();
        
        header('Vary: Accept');
        if(isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
        {
            header('Content-type: application/json');
        } 
        else 
        {
            header('Content-type: text/plain');
        }

        if(!isset($_GET["id"])) 
        {
            throw new CHttpException(500, "Ошибка запроса. Обновите страницу и попробуйте ещё раз");
        }
        $XUploadForm = new XUploadForm;
        
        $fileValidator = CValidator::createValidator('FileValidator', $XUploadForm, 'file', 
            array(
                'maxSize'=>$model->maxSize,
                'types'=>$model->fileTypes,
                'mimeTypes'=>$model->mimeTypes,
                'tooLarge'=>Yii::t('yii','Размер файла "{file}" слишком велик, он не должен превышать 5MB.'),
            ));
        $XUploadForm->validatorList->add($fileValidator);
        
        $XUploadForm->file = CUploadedFile::getInstance($XUploadForm, 'file');

        if($XUploadForm->file !== null) 
        {
            $XUploadForm->mime_type = $XUploadForm->file->getType();
            $XUploadForm->size = $XUploadForm->file->getSize();
            $XUploadForm->name = $XUploadForm->file->getName();

            $fileSaveName = md5(Yii::app()->user->id . microtime() . $XUploadForm->name);
            $fileSaveName .= "." . $XUploadForm->file->getExtensionName();
            
            $model->imageBehaviorAttribute = $fileSaveName;
            
            if($XUploadForm->validate())
            {
                $model->createStorageIfNotExists();
                
                $XUploadForm->file->saveAs($model->savePathAlias . '/' . $fileSaveName);

                $model->createThumbnailForImage($fileSaveName);
                
                $model->mime_type = $XUploadForm->mime_type;
                $model->file_size = $XUploadForm->size;
                $model->orig_name = $XUploadForm->name;
                $model->file_name = $fileSaveName;
                $model->product_id = $_GET["id"];
                
                if(!$model->save())
                    throw new CHttpException( 500, "Ошибка при сохранении данных");

                echo json_encode(array(array(
                    "id" => $model->id,
                    "name" => $XUploadForm->name,
                    "type" => $XUploadForm->mime_type,
                    "size" => $XUploadForm->size,
                    "url" => $model->imageUrl,
                    "thumbnail_url" => $model->imageThumbUrl,
                    "delete_url" => $this->createUrl("upload", array(
                        "_method" => "delete",
                        "file" => $fileSaveName
                    )),
                    "delete_type" => "POST"
                )));
            }
            else
            {
                echo json_encode(array(
                    array( "error" => $XUploadForm->getErrors('file'),
                )));
                Yii::log( "XUploadAction: ".CVarDumper::dumpAsString($XUploadForm->getErrors()),
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