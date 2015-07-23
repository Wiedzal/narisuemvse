/**
||	@name JQuery VIX Gallery
||	@version v1.0
||	@date January 29, 2015
||	@author Zaporozhets Vladislav
||	@email zaporozhets-vlad@rambler.ru
*/


(function( $ ) {
	$.fn.vix_gallery = function(options) {
		var settings = $.extend({
			imgWidth: 760,
			background: false,
			captionClass: 'vix-caption',
			imageArray: [],
			activeImage: 0
		}, options);
		
		var jQueryMatchedObj = $(this);
		
		$(document).click(function(event) 
		{
			if ($(event.target).closest(".vix-modal").length) return;
			removeModal();
			event.stopPropagation();
		});
		
		function init()
		{
			start(this, jQueryMatchedObj);
			return false;
		}
		
		function start(click, obj) 
		{
			
			settings.imageArray.length = 0;
			settings.activeImage = 0;
			
			for (var i = 0; i < obj.size(); i++ ) {
				settings.imageArray.push(new Array(
					obj.eq(i).attr('href'),
					obj.eq(i).attr('title'),
					obj.eq(i).find('.'+settings.captionClass).text(),
					obj.eq(i).attr('data-date')
				));
			}
			
			while ( settings.imageArray[settings.activeImage][0] != click.getAttribute('href') ) {
				settings.activeImage++;
			}
			
			createModal();
			setImage();
		}
		
		function setImage()
		{
			$('.vix-loader').show();
			$('.vix-image').hide();
			var objImagePreloader = new Image();
			objImagePreloader.onload = function() {
				$('.vix-image').attr('src',settings.imageArray[settings.activeImage][0]);
				// Perfomance an effect in the image container resizing it
				_resize_container_image_box(objImagePreloader.width,objImagePreloader.height);
				//	clear onLoad, IE behaves irratically with animated gifs otherwise
				objImagePreloader.onload=function(){};
			
			};
			objImagePreloader.src = settings.imageArray[settings.activeImage][0];
			//$('.vix-image').attr('src',settings.imageArray[settings.activeImage][0]).on('load', function(){
			//	_resize_container_image_box(objImagePreloader.width,objImagePreloader.height);
			//})
		}
		
		function _resize_container_image_box(intImageWidth,intImageHeight) {
			// Get current width and height
			var intCurrentWidth = $('.vix-image-wrap').width();
			var intCurrentHeight = $('.vix-image-wrap').height();
			
			// Get the width and height of the selected image plus the padding
			
			var widthProp = (settings.imgWidth / intImageWidth);
			var newHeight = intImageHeight * widthProp;
			
			if(intImageWidth > settings.imgWidth)
			{
				var widthProp = (settings.imgWidth / intImageWidth);
				var newHeight = intImageHeight * widthProp;
			} else {
				var newHeight = intImageHeight;
			}
			
			// Diferences
			//var intDiffW = intCurrentWidth - intWidth;
			//var intDiffH = intCurrentHeight - intHeight;
			// Perfomance the effect
			//_show_image_data();
			setTitle();
			$('.vix-image-wrap').animate({ height: newHeight }, 300, function() { 
				$('.vix-loader').hide();
				_show_image();
			});
			
		};
		
		function _show_image() {
			$('.vix-image').fadeIn('fast');
			_set_navigation();
			//_preload_neighbor_images();
		};
		
		function _show_image_data()
		{
			$('.vix-image-caption').text(settings.imageArray[settings.activeImage][2])
		}
		
		function _set_navigation() {
			
			// Show the prev button, if not the first image in set
			
			
			
			$('.vix-btn-prev').unbind()
				.bind('click',function() {
					if ( settings.activeImage == 0 ) {
						settings.activeImage = settings.imageArray.length - 1;
					} else {
						settings.activeImage = settings.activeImage - 1;
					}
					setImage();
					return false;
				});
				
			$('.vix-btn-next, .vix-image').unbind()
				.bind('click',function() {
					if ( settings.activeImage == settings.imageArray.length - 1 ) {
						settings.activeImage = 0;
					} else {
						settings.activeImage = settings.activeImage + 1;
					}
					setImage();
					return false;
				});
		}
		
		function createModal()
		{
			// Создаем подложку
			$('body').width($(document).width())
			$('body').css('overflow', 'hidden');
			$('body').append('<div class="vix-overlay"></div>');
			if(settings.background) {
				$('.vix-overlay').css('background-color', settings.background);
			}
			
			// Общая структура модального окна
			$('.vix-overlay').append('<div class="vix-modal-wrap"><div class="vix-modal"><div class="vix-modal-title"></div><div class="vix-modal-body"></div></div></div>');
			
			// Заполняем заголовок (текст и крестик)
			//index = obj.index();
			//count = obj.size();
			$('.vix-modal-title').append('<div class="vix-title-caption"></div>');
			$('.vix-modal-title').append('<div class="vix-close"></div>');
			$('.vix-close').on('click', function(){
				removeModal();
			})
			
			// Заполняем контент
			$('.vix-modal-body').append('<div class="vix-body-top"><button class="vix-btn vix-btn-prev"></button><div class="vix-image-wrap"></div><button class="vix-btn vix-btn-next"></button></div>');
			
			$('.vix-image-wrap').append('<div class="vix-loader"></div>');
			$('.vix-image-wrap').append('<img src="" alt="" class="vix-image" />');
			
			// DIV для описания картинки
			//$('.vix-modal-body').append('<div class="vix-image-caption"></div>')
			
			// Показываем окно
			$('.vix-overlay').fadeIn('fast');
		}
		
		function removeModal() 
		{
			$('.vix-overlay').fadeOut('fast', function(){
				$(this).remove();
				$('body').css('overflow', 'auto');
				$('body').width("auto")
			})
		}
		
		function setTitle()
		{
			$('.vix-title-caption').text('Фотография '+ (settings.activeImage+1) +' из '+ settings.imageArray.length);
			//$('.vix-image-date').text(settings.imageArray[settings.activeImage][3]);
			
		}
		
		return $(this).unbind('click').click(init);
		
	};
})(jQuery);