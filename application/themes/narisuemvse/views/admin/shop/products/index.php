<? if(empty($model->search()->itemCount)) : ?>
    <div class="note note-info">
        <?=Yii::t('app', 'У вас ещё не создано ни одного товара. Вы можете')?> <a href="<?=$this->createUrl('products/create')?>" class="medium"><?=Yii::t('app', 'создать товар')?></a>
    </div>
<? else : ?>
    
    <? $this->renderPartial('_grid', array('model'=>$model));?>
    
    <div class="form-actions mt20">
        <a href="<?=$this->createUrl('products/create')?>" class="btn btn-success"><?=Yii::t('app', 'Добавить товар')?></a>
    </div>
<? endif; ?>