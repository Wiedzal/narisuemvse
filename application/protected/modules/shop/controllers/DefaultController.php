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
        //die('actionCategory');
        // Ищем категорию по переданному пути
        $category = ShopCategories::model()->findByPath($category);
        if (!$category)
            throw new CHttpException(404, Yii::t('app', 'Категория не найдена'));
 
        //var_dump($category);
        //var_dump($category->url);
        //die('categ');
 
        $criteria = new CDbCriteria();
        //var_dump($category->id);
        //var_dump($category->getChildsArray());
        //var_dump(array_merge(array($category->id), $category->getChildsArray()));
        //die;
        /*$criteria->addInCondition('t.category_id', array_merge(array($category->id), $category->getChildsArray()));
 
        $dataProvider = new CActiveDataProvider(ShopProduct::model()->cache(3600), array(
            'criteria'=>$criteria,
            'pagination'=> array(
                'pageSize'=>self::PRODUCTS_PER_PAGE,
                'pageVar'=>'page',
            )
        ));*/
 
        $this->breadcrumbs = array_merge($this->breadcrumbs, $category->breadcrumbs);
        $this->pageTitle = $category->title;
        $this->pageDescription = $category->meta_description;
        $this->pageKeywords = $category->meta_keywords;
        $this->render('category', array(
            //'dataProvider'=>$dataProvider,
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