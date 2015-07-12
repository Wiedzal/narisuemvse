<? if(Yii::app()->user->hasFlash('success')): ?>
    <div class="note note-success mb30">
        <div class="note-close"></div>
         <?=Yii::app()->user->getFlash('success');?>
    </div>
<? endif; ?>

<? if(empty($models)) : ?>

<div class="note note-info">
    <?=Yii::t('app', 'У вас ещё не создано ни одной новости. Вы можете')?> <a href="<?=$this->createUrl('default/create')?>" class="medium"><?=Yii::t('app', 'создать новость')?></a>
</div>

<? else : ?>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="w100"><?=Yii::t('app', 'Изображение')?></th>
                <th><?=Yii::t('app', 'Заголовок')?></th>
                <th><?=Yii::t('app', 'Дата добавления')?></th>
                <th><?=Yii::t('app', 'Видимость')?></th>
                <th class="w150"><?=Yii::t('app', 'Действия')?></th>
            </tr>
        </thead>
        <tbody>
        <? foreach($models as $model) : ?>
            <tr>
                <td>
                    <? if($model->imageThumbUrl) : ?>
                        <?=CHtml::image($model->imageThumbUrl, $model->title, array('class'=>'w75'))?>
                    <? else: ?>
                        <?=CHtml::image(Yii::app()->theme->baseUrl . '/public/admin/img/no-photo/300x200.png', '', array('class'=>'w75'))?>
                    <? endif; ?>
                </td>
                <td class="item-title"><?=$model->title?></td>
                <td><?=date('d.m.Y H:i', strtotime($model->created_at))?></td>
                <td>
                    <?= $model->is_show == (int)TRUE ? Yii::t('app', 'Отображается') : Yii::t('app', 'Скрыта');?>
                </td>
                <td class="w175">
                    <a href="<?=$this->createUrl('default/view/id/'.$model->id)?>" class="btn btn-info btn-icon"><i class="fa fa-eye"></i></a>
                    <a href="<?=$this->createUrl('default/edit/id/'.$model->id)?>" class="btn btn-success btn-icon"><i class="fa fa-pencil"></i></a>  
                    <?=CHtml::linkButton('<i class="fa fa-trash"></i>', array(
                        'submit' => array('default/delete', 'id' => $model->id),
                        'params' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        'confirm' => Yii::t('app', 'Удалить новость?'),
                        'class'=>'btn btn-error btn-icon'));
                    ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
    
    <? $this->widget('CLinkPager', array(
        'pages' => $pages, 
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>', 
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'header' => '',
            'htmlOptions' => array(
                'class' => 'pagination'
            ),
    ))?>

    <div class="form-actions mt20">
        <a href="<?=$this->createUrl('default/create')?>" class="btn btn-success"><?=Yii::t('app', 'Добавить новость')?></a>
    </div>

<? endif; ?>