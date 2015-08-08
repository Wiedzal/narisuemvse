<?php

class DefaultController extends Controller
{
    const PRODUCTS_PER_PAGE = 20;
    
    public function init() 
    {
        $this->breadcrumbs = array(Yii::t('app', 'Каталог товаров') => $this->createUrl('index'));
        parent::init();
    }
    
    public function actionIndex()
    {
        die('actionIndex');
        // Вывод списка всех товаров
    }
 
    public function actionCategory($category)
    {
        //Ищем категорию по переданному пути
        $category = ShopCategories::model()->findByPath($category);
        if (!$category)
            throw new CHttpException(404, Yii::t('app', 'Категория не найдена'));

        $this->breadcrumbs = array_merge($this->breadcrumbs, $category->breadcrumbs);
        $this->pageTitle = $category->title;
        $this->pageDescription = $category->meta_description;
        $this->pageKeywords = $category->meta_keywords;   
            
        $criteria = new CDbCriteria();
        
        $this->render('category', array(
            'category'=>$category,
        ));
    }
 
    public function actionView($id)
    {
        //die('actionShow');
        // Отображение страницы товара
        //$product = ShopProduct::model()->with('category')->findByPk($id);
 
        var_dump(Yii::app()->request->requestUri);die;
        // Защита от зеркал страниц
        if (Yii::app()->request->requestUri != $product->url) 
            $this->redirect($product->url);
 
        if (!$product) 
            throw new CHttpException(404, 'Not found');
 
        $this->render('view', array(
            'product'=>$product,
        ));
    }
}