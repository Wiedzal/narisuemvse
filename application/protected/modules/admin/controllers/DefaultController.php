<?php

class DefaultController extends AdminModuleController
{
	public function init() 
	{
		parent::init();
	}

	public function actionIndex()
	{
		$this->checkAccess('Admin');
		
		$this->pageTitle = 'Панель управления';
		$this->breadcrumbs = array_merge($this->breadcrumbs, array('Панель управления'));
		
		$this->render('index');
	}
}