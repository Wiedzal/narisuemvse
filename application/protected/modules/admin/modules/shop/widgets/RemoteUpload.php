<?php 

class RemoteUpload extends CWidget
{
	/**
     * @var string Ссылка на экшн-обработчик загрузки 
     */
    public $url;	

    public function init() 
    {
        parent::init();
    }
    
    public function run() 
    {
        parent::run();
    }

    public function include_js($js = true)
    {
		if (!(bool)$js)
		{
			return;
		}
        $ScriptFile = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'CitiesWidget';
        
        if (file_exists($ScriptFile)) 
        {
            $path = Yii::app()->assetManager->publish($ScriptFile) . '/';

            Yii::app()->clientScript->registerScriptFile(
                $path . 'js/cities.js', CClientScript::POS_HEAD
            );
        
        }
    }
}