<?php

class CategoriesController extends AdminModuleController
{
	public function init() 
    {
        $this->breadcrumbs = array(Yii::t('app', 'Категории') => $this->createUrl('index'));
        parent::init();
    }
    
    public function actionIndex()
	{
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Управление категориями')));
        $this->pageTitle = Yii::t('app', 'Категории');
        
        $models = ShopCategories::model()->getFullBranch();
        //var_dump($models);die;
        Yii::app()->clientScript->registerPackage('jquery.tree');
        
        $this->render('index', array(
            'models' => $models,
        ));
	}
    
    public function actionCreate()
    {
        $this->breadcrumbs = array_merge($this->breadcrumbs, array(Yii::t('app', 'Создание категории')));
        $this->pageTitle = Yii::t('app', 'Создание категории');

        $model = new ShopCategories();
        $possibleParents = $model->getPossibleParents();

        if (!empty($_POST) && array_key_exists('ShopCategories', $_POST))
        {
            $this->performAjaxValidation($model);
            $model->attributes = $_POST['ShopCategories'];
            //var_dump($model->attributes);die;
            if ($model->validate())
            {
                if($model->save())
                {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Категория успешно добавлена'));
                    Yii::app()->request->redirect($this->createUrl('index'));
                }
            }
        }
        //var_dump($possibleParents);die;
        $this->render('create', array(
            'model' => $model,
            'possibleParents' => $possibleParents
        ));
    }
    
    public function actionEdit($id)
    {
        $this->pageTitle = Yii::t('app', 'Редактирование категории');

        $model = ShopCategories::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(404, Yii::t('app', 'Категория не найдена'));
        
        //var_dump($model->url);die;
        // var_dump($model->path);die;
        //var_dump($model->breadcrumbs);
        //die;
        $this->breadcrumbs = array_merge($this->breadcrumbs, array('Редактирование категории "' . $model->title . '"'));
        
        $possibleParents = $model->getPossibleParents();

        if (!empty($_POST) && array_key_exists('ShopCategories', $_POST))
        {
            $this->performAjaxValidation($model);
            $model->attributes = $_POST['ShopCategories'];
            //var_dump($model->attributes);die;
            if ($model->validate())
            {
                if($model->save())
                {
                    Yii::app()->user->setFlash('success', 'Категория "' . $model->title . '" успешно отредактирована');
                    Yii::app()->request->redirect($this->createUrl('index'));
                }
            }
        }
        //var_dump($possibleParents);die;
        $this->render('edit', array(
            'model' => $model,
            'possibleParents' => $possibleParents
        ));
    }
    
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='create-category-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}