<div class="tabs mb20">
	<div class="tabs-list">
		<ul>
			<li>
				<a href="#main" class="active">Основное <span class="required">*</span></a>
			</li>
			<li>
				<a href="#tech">Характеристики <span class="required">*</span></a>
			</li>
			<li>
				<a href="#descr">Описание</a>
			</li>
			<li>
				<a href="#img">Изображения</a>
			</li>
			<li>
				<a href="#seo">SEO <span class="required">*</span></a>
			</li>
		</ul>
	</div>
	<div class="tab-content">
		<? # ОСНОВНОЕ ?>
		<div class="tab-content-in active" id="main">
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Название 
				</div>
				<div class="col-9">
					<?=$item['title']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Производитель
				</div>
				<div class="col-9">
					<?=$item['manufacturer']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Ссылка 
				</div>
				<div class="col-9">
					<?=$item['ufUrl']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Категория
				</div>
				<div class="col-9">
					<?=$item['category']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Наличие
				</div>
				<div class="col-9">
					<?=$item['available'] ? 'Да' : 'Нет'?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3">
					Лидер продаж
				</div>
				<div class="col-8">
					<?=$item['lider'] ? 'Да' : 'Нет'?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3">
					Отображать на сайте
				</div>
				<div class="col-8">
					<?=$item['visibility'] ? 'Да' : 'Нет'?>
				</div>
			</div>
		</div>
		
		<? # ХАРАКТЕРИСТИКИ ?>
		<div class="tab-content-in" id="tech">
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Артикул
				</div>
				<div class="col-9">
					<?=$item['article']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Цена
				</div>
				<div class="col-9">
					<?=$item['price']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Старая цена
				</div>
				<div class="col-9">
					<?=$item['oldPrice']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Модель
				</div>
				<div class="col-9">
					<?=$item['model']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Страна
				</div>
				<div class="col-9">
					<?=$item['country']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Гарантия
				</div>
				<div class="col-9">
					<?=$item['garanty']?>
				</div>
			</div>
		</div>
		
		<? # ОПИСАНИЕ ?>
		<div class="tab-content-in" id="descr">
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Подробное описание
				</div>
				<div class="col-9">
					<?=$item['descr']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Технические характеристики
				</div>
				<div class="col-9">
					<?=$item['tech']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Доставка / установка
				</div>
				<div class="col-9">
					<?=$item['delivery']?>
				</div>
			</div>
		</div>
		
		<? # ИЗОБРАЖЕНИЯ ?>
		<div class="tab-content-in" id="img">

			<h3 class="mb20">Основное изображение</h3>
			<div class="row form-group img-wrap">
				<div class="col-4">
					<? if(!empty($item['imgUrl'])  && file_exists(FCPATH . '/assets/uploads/files' . $item['imgUrl'])) : ?>
						<?=img(array('src'=>'/assets/uploads/files' . $item['imgUrl'], 'style'=>'width:100%'))?>
					<? else : ?>
						<?=img('/assets/admin/img/no-photo/500x500.png')?>
					<? endif; ?>
				</div>
			</div>
			
			<hr class="mt30 mb30"/>
			
			<h3 class="mb20">Дополнительные изображения</h3>
			<? if(array_key_exists('imgs', $item) && !empty($item['imgs'])) : ?>
				<? foreach($item['imgs'] as $img) : ?>
					<div class="row form-group img-wrap">
						<div class="col-4">
							<? if(!empty($img['url'])  && file_exists(FCPATH . '/assets/uploads/files' . $img['url'])) : ?>
								<?=img(array('src'=>'/assets/uploads/files' . $img['url'], 'style'=>'width:100%'))?>
							<? else : ?>
								<?=img('/assets/admin/img/no-photo/500x500.png')?>
							<? endif; ?>
						</div>
					</div>
				<? endforeach; ?>
			<? endif; ?>
		</div>
		
		<? # SEO ?>
		<div class="tab-content-in" id="seo">
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Title 
					<h6 class="form-info text-gray">Максимальная длина 255 символов</h6>
				</div>
				<div class="col-9">
					<?=$item['mTitle']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Ключевые слова
				</div>
				<div class="col-9">
					<?=$item['mKeywords']?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-3 form-collabel">
					Ключевое описание
				</div>
				<div class="col-9">
					<?=$item['mDescription']?>
				</div>
			</div>
		</div>
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
				Вы действительно хотите удалить запись <span class="medium">"<?=$item['title']?>"</span>?
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