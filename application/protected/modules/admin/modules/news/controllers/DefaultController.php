<?php

class DefaultController extends AdminModuleController
{
    public function init() 
    {
        $this->breadcrumbs = array(Yii::t('app', 'Новости') => $this->createUrl('index'));
        parent::init();
    }

    public function actionIndex()
    {
        //var_dump(Yii::app()->controller->module->id);die;
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Управление новостями'));
        $this->pageTitle = Yii::t('app', 'Новости');

        $criteria = new CDbCriteria();
        $criteria->order = 'created_at DESC';
        $count = News::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->PageSize = 10;
        $pages->applyLimit($criteria);

        $models = News::model()->findAll($criteria);

        $this->render('index', array(
            'models' => $models,
            'pages' => $pages
        ));
    }
    
    public function actionCreate()
    {
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Создание новости'));
        $this->pageTitle = Yii::t('app', 'Создание новости');

        $model = new News();

        if (Yii::app()->request->getPost('News'))
        {
            $model->attributes = Yii::app()->request->getPost('News');

            if ($model->validate())
            {
                if($model->save())
                {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Новость успешно добавлена'));
                    Yii::app()->request->redirect($this->createUrl('index'));
                }
            }
        }

        $this->render('create', array(
            'model' => $model
        ));
    }
    
    public function actionEdit($id)
    {
        $this->pageTitle = Yii::t('app', 'Редактирование новости');

        $model = News::model()->findByPk($id);

        if($model == null)
        {
            throw new CHttpException(404, Yii::t('app', 'Страница не найдена'));
        }
        
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Редактирование "' . $model->title . '"'));
        
        if (!empty($_POST) && array_key_exists('News', $_POST))
        {
            //var_dump($_POST);die;
            $model->attributes = $_POST['News'];
            //var_dump($model->attributes);die;
            if ($model->validate())
            {
                if($model->save())
                {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Новость успешно отредактирована'));
                    Yii::app()->request->redirect($this->createUrl('index'));
                }
            }
        }

        $this->render('edit', array(
            'model' => $model
        ));
    }
    
    public function actionView($id)
	{
        $this->pageTitle = Yii::t('app', 'Просмотр новости');
        
        $model = News::model()->findByPk($id);
        
        if($model == null)
        {
            throw new CHttpException(404, Yii::t('app', 'Страница не найдена'));
        }
        
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Просмотр "' . $model->title . '"'));
        
        $this->render('view', array(
            'model' => $model
        ));
	}
    
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            if (isset($_POST['YII_CSRF_TOKEN']) && ($_POST['YII_CSRF_TOKEN'] === Yii::app()->request->csrfToken))
            {
                $model = News::model()->findByPk($id);
                if ($model == null)
                {
                    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.'); 
                }
                
                $model->delete();
                Yii::app()->user->setFlash('success', Yii::t('app', 'Новость успешно удалена'));
                
                $this->redirect($this->createUrl('index'));    
            }            
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }
    
    public function actionDeletePicture($id)
    {
        if(Yii::app()->request->isPostRequest)
        {            
            if ((isset($_POST['YII_CSRF_TOKEN'])) && ($_POST['YII_CSRF_TOKEN'] === Yii::app()->request->csrfToken))
            {
                $model = News::model()->findByPk($id);
                $model->imageBehavior->deleteFile();
                $model->image = null;
                $model->save();
                
                $this->redirect($this->createUrl('default/edit/id/' . $id));    
            }            
        }
        else
        {
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }  
}