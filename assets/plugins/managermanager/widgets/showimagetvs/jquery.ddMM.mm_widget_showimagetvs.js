/**
 * jQuery ddMM.mm_widget_showimagetvs Plugin
 * @version 1.0.1 (2014-05-07)
 * 
 * @uses jQuery 1.9.1
 * @uses $.ddMM 1.1.2
 * @uses $.ddTools 1.8.1
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
$.ddMM.mm_widget_showimagetvs = {
	//Параметры по умолчанию
	defaults: {
		//Если у вас установлен PHPThumb, вы можете указать url, где он находится, адрес превью будет обращён к нему с передачей url исходной картинки, ширины и высоты.
		thumbnailerUrl: '',
		//Максимальная ширина превьюшки в px.
		width: 300,
		//Максимальная высота превьюшки в px.
		height: 100
	},
	templates: {
		previewContainer: '<div class="tvimage"><img src="" style="display: none; max-width: [+width+]px; max-height: [+height+]px; margin: 4px 0; cursor: pointer;" /></div>'
	}
};

/**
 * jQuery.fn.mm_widget_showimagetvs Plugin
 * @version 1.0.2 (2014-05-07)
 * 
 * @description Делает превьюшку для tv.
 * 
 * Параметры передаются в виде plain object.
 * @param thumbnailerUrl {string} - Если у вас установлен PHPThumb, вы можете указать url, где он находится, адрес превью будет обращён к нему с передачей url исходной картинки, ширины и высоты. Default: ''.
 * @param width {integer} - Максимальная ширина превьюшки в px. Default: 300.
 * @param height {integer} - Максимальная высота превьюшки в px. Default: 100.
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */
$.fn.mm_widget_showimagetvs = function(params){
	var _this = $.ddMM.mm_widget_showimagetvs;
	
	//Обрабатываем параметры
	params = $.extend({}, _this.defaults, params || {});
	
	return $(this).addClass('imageField').each(function(){
		var $this = $(this),
			// Create a new preview
			$previewContainer = $($.ddTools.parseChunkAssoc(_this.templates.previewContainer, params));
		
		$previewContainer.appendTo($this.parents('td:first'));
		
		// Attach a browse event to the picture, so it can trigger too
		$previewContainer.find('img').on('click', function(){
			BrowseServer($this.attr('id'));
		}).on('error', function(){
			$(this).hide();
		});
	}).on('change.mm_widget_showimagetvs load.mm_widget_showimagetvs', function(){
		var $this = $(this),
			// Get the new URL
			url = $.trim($this.val()),
			$img = $this.parents('td:first').find('.tvimage img');
		
		$this.data('lastvalue', url);
		
		if (url.length > 0 && url.search(/https?:\/\//i) == -1 && url.search(/\//) != 0){
			url = $.ddMM.config.site_url + url;
		}
		// If we have a PHPThumb URL
		if (url.length > 0 && params.thumbnailerUrl.length > 0){
			url = params.thumbnailerUrl + '?src=' + escape(url) + '&w=' + params.width + '&h=' + params.height;
		}
		
		$img.attr('src', url);
		
		if (url.length > 0){
			$img.show();
		}else{
			$img.hide();
		}
	}).trigger('load');
};

//On document.ready
$(function(){
	// Monitor the image TVs for changes
	setInterval(function(){
		$('.imageField').each(function(){
			var $this = $(this);
			
			if ($this.val() != $this.data('lastvalue')){
				$this.trigger('change');
			}
		});
	}, 250);
});
})(jQuery);