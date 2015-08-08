<? if(isset($this->breadcrumbs)) : ?>
    <? $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
            'homeLink' => CHtml::link('Главная','/admin' ),
        )); ?>
<? endif ?>

Проверка вложенности категорий, названий страниц и крошек.
<br/>
<?=$category->title;?>
<br/>
<?=$category->url;?>