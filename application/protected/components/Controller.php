<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $base_uri;
	
	public function init()
	{
		$this->base_uri = str_replace(Yii::app()->baseUrl, '', $_SERVER['REQUEST_URI']); 
		parent::init();
	}
	
	public function checkAccess($operation = '')
	{
		if (!Yii::app()->user->checkAccess($operation))
		{
			if (Yii::app()->user->isGuest)
			{
				Yii::app()->user->returnUrl = $_SERVER['REQUEST_URI'];

				$this->redirect('/site/login');    
			}
			else
			{
				throw new CHttpException(403, 'Forbidden');
			}   
		}
	}
}