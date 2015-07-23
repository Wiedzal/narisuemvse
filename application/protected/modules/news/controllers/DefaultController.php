<?php

class DefaultController extends Controller
{
	public function init() 
    {
        $this->breadcrumbs = array(Yii::t('app', 'Новости') => $this->createUrl('index'));
        parent::init();
    }
    
    public function actionIndex()
	{
		$this->render('index');
	}
    
    public function actionView($id)
	{
        $this->breadcrumbs = array_merge($this->breadcrumbs, array($model->title));
        $this->pageTitle = $model->lang->title;
    
        $criteria = new CDbCriteria();
        $criteria->condition ='is_show=:is_show';
        $criteria->params = array(':is_show'=>(int)TRUE);
        
        $model = News::model()->find($criteria);
    
        $this->pageDescription = $model->meta_description;
        $this->pageKeywords = $model->meta_keywords;
        
        $this->render('view', array(
            'model' => $model
        ));
    }
}