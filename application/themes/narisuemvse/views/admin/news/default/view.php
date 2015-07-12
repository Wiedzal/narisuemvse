<div class="page-preview">
    <div class="img">
        <? if($model->imageThumbUrl) : ?>
            <?=CHtml::image($model->imageThumbUrl, $model->title)?>
        <? else: ?>
            <?=CHtml::image(Yii::app()->theme->baseUrl . '/public/admin/img/no-photo/300x200.png')?>
        <? endif; ?>
    </div>
    <div class="descr">
        <h3 class="mb5"><?=CHtml::encode($model->title)?></h3>
        <div class="mb20">
            <? if($model->is_show) : ?>
                <i class="fa fa-fw fa-eye text-success"></i>
            <? else : ?>
                <i class="fa fa-fw fa-eye-slash text-error"></i>
            <? endif; ?>
            <i class="mr10"></i>
            <i class="fa fa-fw fa-calendar mr5 text-gray"></i> <?=date('d.m.Y', strtotime($model->created_at))?>
            <i class="mr15"></i>
            <i class="fa fa-fw fa-clock-o mr5 text-gray"></i> <?=date('H:i', strtotime($model->created_at))?>
        </div>
        <div class="italic"><?=CHtml::encode($model->brief_text);?></div>
    </div>
</div>

<div class="text-editor">
    <?=$model->text;?>
</div>

<hr class="mt30 mb20" />

<div class="form-actions">
    <a href="<?=$this->createUrl('default/edit/id/'.$model->id)?>" class="btn btn-success">Редактировать</a>
    <a href="<?=$this->createUrl('default/index')?>" class="btn">Вернуться назад</a>
    <?=CHtml::linkButton('<i class="fa fa-trash"></i>', array(
        'submit' => array('default/delete', 'id' => $model->id),
        'params' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
        'confirm' => Yii::t('app', 'Удалить новость?'),
        'class'=>'btn btn-icon btn-error right'));
    ?>
</div>