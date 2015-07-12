<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminModuleController extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    
    public $base_uri;
    
    protected $js = array();
    
    public function init()
    {
        $this->checkAccess('Admin');
        $this->base_uri = str_replace(Yii::app()->baseUrl, '', $_SERVER['REQUEST_URI']); 
        $this->layout = '//admin/layouts/l_column';
        parent::init();
    }
    
    public function beforeRender($view) 
    {
        parent::beforeRender($view);
        $this->includeScriptFile();
        return true;
    }
    
    /**
     * Проверка доступа к запрошиваемому экшену
     */
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
    
    /**
     * Подключение js-файлов
     */
    public function includeScriptFile()
    {
        if ($this->module === null)
        {
            $path = Yii::app()->getBasePath();
        }
        else
        {
            $path = $this->module->getBasePath();
        }

        #Подключение js для экшна. Имя файла соответствует имени экшна.js
        $actionScriptFile = $path . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . $this->action->id . '.js';

        if (file_exists($actionScriptFile)) 
        {
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish($actionScriptFile), CClientScript::POS_HEAD
            );
        }

        #Подключение js для контроллера. Имя файла соответствует имени контроллера.js
        $sharedScriptFile = $path . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . 'action' . ucfirst($this->action->id) . '.js';

        if (file_exists($sharedScriptFile)) 
        {
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish($sharedScriptFile), CClientScript::POS_HEAD
            );            
        }
        
        foreach ($this->js as $name)
        {
            $ScriptFile = $path . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR . $name . '.js';

            if (file_exists($ScriptFile)) 
            {
                Yii::app()->clientScript->registerScriptFile(
                    Yii::app()->assetManager->publish($ScriptFile), CClientScript::POS_HEAD
                );            
            }            
        }
        
        if ($this->module !== null)
        {
            $moduleScriptFile = $path . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'module' . '.js';

            if (file_exists($moduleScriptFile)) 
            {
                Yii::app()->clientScript->registerScriptFile(
                    Yii::app()->assetManager->publish($moduleScriptFile), CClientScript::POS_HEAD
                );            
            }
        }
    }
}