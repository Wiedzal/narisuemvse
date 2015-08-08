<? if(Yii::app()->user->hasFlash('success')): ?>
    <div class="note note-success mb30">
        <div class="note-close"></div>
         <?=Yii::app()->user->getFlash('success');?>
    </div>
<? endif; ?>

<? if(empty($models)) : ?>

    <div class="note note-info">
        <?=Yii::t('app', 'У вас ещё не создано ни одной категории. Вы можете')?> <a href="<?=$this->createUrl('categories/create')?>" class="medium"><?=Yii::t('app', 'создать категорию')?></a>
    </div>

<? else : ?>

    <table class="table table-hover" id="category-tree">
        <thead>
            <tr>
                <th class="w50">#</th>
                <th class="w50"></th>
                <th class="w100"><?=Yii::t('app', 'Изображение')?></th>
                <th><?=Yii::t('app', 'Название')?></th>
                <th><?=Yii::t('app', 'Адрес')?></th>
                <th class="w150"><?=Yii::t('app', 'Действия')?></th>
            </tr>
        </thead>
        <tbody>
        <? foreach($models as $model) : ?>
           <tr 
                row="<?=$model->id?>" 
                parent_row="<?=$model->parent_id?>" 
                class="row-container <?=(count($model->children) > 0) ? 'collapsed' : null ?>"
                style="display:<?=($model->tree_level > 2) ? 'none' : 'table_row' ?>"
            >
                <td><?=$model->id?></td>
                <? $marginLeft = ($model->tree_level > 2) ? (($model->tree_level-1) * 30).'px' : '15px'; ?>
                <td scope="col" class="first" style="padding-left:<?=$marginLeft?>;">
                    <div class="tc-element" style="position:relative;"></div>
                    <div class="tc-text title"></div>
                </td>
                <td>
                    <? if($model->imageThumbUrl) : ?>
                        <?=CHtml::image($model->imageThumbUrl, $model->title, array('class'=>'w75'))?>
                    <? else: ?>
                        <?=CHtml::image(Yii::app()->theme->baseUrl . '/public/admin/img/no-photo/300x200.png', '', array('class'=>'w75'))?>
                    <? endif; ?>
                </td>
                <td class="item-title"><?=$model->title;?></td>
                <td>
                    <a href="<?=Yii::app()->createAbsoluteUrl($model->url)?>" class="medium"><?=$model->url?></a>
                </td>
                <td class="w175">
                    <a href="<?=$this->createUrl('categories/view/id/'.$model->id)?>" class="btn btn-info btn-icon"><i class="fa fa-eye"></i></a>
                    <a href="<?=$this->createUrl('categories/edit/id/'.$model->id)?>" class="btn btn-success btn-icon"><i class="fa fa-pencil"></i></a>  
                    <?=CHtml::linkButton('<i class="fa fa-trash"></i>', array(
                        'submit' => array('categories/delete', 'id' => $model->id),
                        'params' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        'confirm' => Yii::t('app', 'Удалить категорию?'),
                        'class'=>'btn btn-error btn-icon'));
                    ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>

    <div class="form-actions mt20">
        <a href="<?=$this->createUrl('categories/create')?>" class="btn btn-success"><?=Yii::t('app', 'Добавить категорию')?></a>
    </div>

    <script>
        $(function() {
            $('#category-tree').Tree();
        })
    </script>

<? endif; ?>