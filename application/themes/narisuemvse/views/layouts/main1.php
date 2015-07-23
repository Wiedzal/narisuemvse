<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">

<head>

	<title><?=CHtml::encode($this->pageTitle);?></title>
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=1000" />
	
	<link href="<?=Yii::app()->theme->baseUrl ?>/public/site/plugins/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link href="<?=Yii::app()->theme->baseUrl ?>/public/admin/css/reset.css" type="text/css" rel="stylesheet" />
	<link href="<?=Yii::app()->theme->baseUrl ?>/public/admin/css/style.css" type="text/css" rel="stylesheet" />

	<link rel="shortcut icon" href="<?=Yii::app()->theme->baseUrl ?>/public/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?=Yii::app()->theme->baseUrl ?>/public/favicon.ico" type="image/x-icon" />
	
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
	
</head>
<body class="login">
	<?=$content?>
</body>
</html>