<div class="row form-group">
	<div class="col-3 form-collabel">
		Название 
	</div>
	<div class="col-9">
		<?= $item['title']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3 form-collabel">
		Ссылка 
	</div>
	<div class="col-9">
		<?= $item['ufUrl']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3 form-collabel">
		Родительская категория
	</div>
	<div class="col-9">
		<?= $item['parent']?>
	</div>
</div>

<div class="row form-group">
	<div class="col-3 form-collabel">
		Отобразить при выборе категории
	</div>
	<div class="col-9">
		<?= $item['is_content_page'] ? 'Товары категории' : 'Подкатегории'?>
	</div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
	<div class="col-3 form-collabel">
		Заголовок страницы
	</div>
	<div class="col-9">
		<?= $item['cTitle']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3 form-collabel">
		Описание страницы
	</div>
	<div class="col-9">
		<?= $item['cDescr']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3">
		Отображать на сайте
	</div>
	<div class="col-8">
		<?= $item['visibility'] ? 'Да' : 'Нет'?>
	</div>
</div>

<hr class="mt30 mb30" />

<div class="row form-group">
	<div class="col-3 form-collabel">
		Title 
	</div>
	<div class="col-9">
		<?= $item['mTitle']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3 form-collabel">
		Ключевые слова
	</div>
	<div class="col-9">
		<?= $item['mKeywords']?>
	</div>
</div>
<div class="row form-group">
	<div class="col-3 form-collabel">
		Ключевое описание
	</div>
	<div class="col-9">
		<?= $item['mDescription']?>
	</div>
</div>

<div class="row form-group">
	<div class="col-3 form-collabel">
		Изображение
		<h6 class="form-info text-gray">Максимальный размер 2Mb</h6>
	</div>
	<div class="col-9">
		<? if(file_exists(FCPATH . '/assets/uploads/categories/' . $item['img'])) : ?>
			<?=img(array('src' =>'/assets/uploads/categories/' . $item['img'], 'style' => 'width:50%'))?>
		<? else : ?>
			<?=img('/assets/admin/img/no-photo/500x500.png')?>
		<? endif; ?>
	</div>
</div>

<div class="form-actions">
	<?=anchor('admin/'.$this->uri->segment(2).'/edit/'.$item['idItem'], 'Редактировать', array('class' => 'btn btn-success'))?>
	<?=anchor('admin/'.$this->uri->segment(2), 'Вернуться назад', array('class' => 'btn'))?>
	<?=anchor('#delModal', '<i class="fa fa-trash"></i>', array('class' => 'btn btn-icon btn-error right', 'data-toggle' => 'modal'))?>
</div>

<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog w500">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-close" data-dismiss="modal" aria-label="Close"></div>
				<div class="modal-title">Подтвердить удаление записи</div>
			</div>
			<?=form_open('admin/'.$this->uri->segment(2).'/delete/'.$item['idItem'], array('id' => 'delForm'))?>
			<div class="modal-body">
				Вы действительно хотите удалить категорию <span class="medium">"<?=$item['title']?>"</span>?
				<br/>Все подкатегории и товары в них будут также удалены!
			</div>
			<div class="modal-footer text-right">
				<button class="btn btn-success">Подтвердить</button>
				<span class="btn btn-error" data-dismiss="modal">Отмена</span>
				<input type="hidden" name="delete" value="delete" />
			</div>
			<?=form_close();?>
		</div>
	</div>
</div>