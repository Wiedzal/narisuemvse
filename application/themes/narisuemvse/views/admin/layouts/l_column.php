<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">

<head>

    <title><?=CHtml::encode($this->pageTitle);?></title>
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?=CHtml::encode($this->pageKeywords);?>" />
    <meta name="description" content="<?=CHtml::encode($this->pageDescription);?>" />
    <meta name="viewport" content="width=1000" />
        
    <link href="<?= Yii::app()->theme->baseUrl ?>/public/admin/css/reset.css" type="text/css" rel="stylesheet" />
    <link href="<?= Yii::app()->theme->baseUrl ?>/public/admin/css/style.css" type="text/css" rel="stylesheet" />

    <link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/public/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="<?= Yii::app()->theme->baseUrl ?>/public/favicon.ico" type="image/x-icon" />

    <script type="text/javascript" src="<?= Yii::app()->theme->baseUrl ?>/public/admin/js/content.js"></script>
    <script type="text/javascript" src="<?= Yii::app()->theme->baseUrl ?>/public/admin/js/js.js"></script>
    
    <? include 'control_head.php' ?>

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
</head>
<body>
<div class="super-wrapper">
    <div class="header">

        <div class="row">
            <div class="col-6">
                <div class="header-title">
                    <a href="<?=Yii::app()->createUrl('/admin')?>"><span><?=Yii::app()->name?></span> панель управления</a>
                </div>
            </div>
            <div class="col-6 text-right">
                <div class="header-user">
                    Добро пожаловать, 
                    <div class="header-dropdown">
                        <a href="javascript:void(0)"><?=Yii::app()->user->username?></a>
                    </div>
                </div>
                <div class="header-nav">
                    <a href="/"><i class="fa fa-home"></i></a>
                    <a href="<?=Yii::app()->createUrl('/admin')?>"><i class="fa fa-th-list"></i></a>
                    <a href="<?=Yii::app()->createUrl('/site/logout')?>"><i class="fa fa-sign-out"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="content clearfix">
        <div class="left-side">
            <ul class="left-menu">
                <li>
                    <a href="javascript:void(0)"><i class="fa fa-fw fa-home"></i>Главная</a>
                    <ul class="submenu">
                        <li>
                            <a href="<?=Yii::app()->createUrl('/admin/login/logout')?>"><i class="fa fa-thumbs-o-up"></i></i>Чёйта там</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)"><i class="fa fa-fw fa-bars"></i>Контент</a>
                    <ul class="submenu">
                        <li>
                            <a href="<?=Yii::app()->createUrl('/admin/news')?>"><i class="fa fa-fw fa-newspaper-o"></i>Новости</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)"><i class="fa fa-fw fa-shopping-cart"></i>Магазин</a>
                    <ul class="submenu">
                        <li>
                            <a href="<?=Yii::app()->createUrl('/admin/shop/categories')?>"><i class="fa fa-fw fa-newspaper-o"></i>Категории</a>
                        </li>
                        <li>
                            <a href="<?=Yii::app()->createUrl('/admin/shop/products')?>"><i class="fa fa-fw fa-newspaper-o"></i>Товары</a>
                        </li>
                        <li>
                            <a href="<?=Yii::app()->createUrl('/admin/shop/orders')?>"><i class="fa fa-fw fa-newspaper-o"></i>Заказы</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="right-side">
            <div class="right-side-in">
                <div class="right-side-top">
                    <? if(isset($this->breadcrumbs)) : ?>
                        <? $this->widget('zii.widgets.CBreadcrumbs', array(
                                'links' => $this->breadcrumbs,
                                'homeLink' => CHtml::link('Главная','/admin' ),
                            )); ?>
                    <? endif ?>
                </div>
                <div class="page-content">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <div class="col-6">
        <div class="cms-info">
            
        </div>
    </div>
    <div class="col-6 text-right">
        <div class="narisuem-info">
            2013-<?=date('Y');?> &copy; 
            Разработка сайта
            <a href="<?=Yii::app()->createUrl('/')?>">Hands of Asshole</a>
        </div>
    </div>
</div>
</body>
</html>