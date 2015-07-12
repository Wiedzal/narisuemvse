<div class="login-form">
	<h3 class="mb20"><?=$this->pageTitle?></h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>
		<div class="form-group">
			<div class="form-caption">Логин:</div>
			<?php echo $form->textField($model,'username', array('class'=>'form-input')); ?>
			<?php echo $form->error($model,'username', array('class'=>'form-error h6')); ?>
		</div>
		<div class="form-group">
			<div class="form-caption">Пароль:</div>
			<?php echo $form->passwordField($model, 'password', array('class'=>'form-input')); ?>
			<?php echo $form->error($model,'password', array('class'=>'form-error h6')); ?>
		</div>
		<div class="row">
			<div class="col-6">
				<button class="btn btn-info">Войти</button>
			</div>
			<div class="col-6 form-collabel text-right">
				<a href="<?=Yii::app()->createUrl('javascript:void(0)')?>" class="text-gray">Забыли пароль?</a>
			</div>		
	<?php $this->endWidget(); ?>
</div>