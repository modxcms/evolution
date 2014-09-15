/**
 * jQuery ddMM.mm_ddHTMLCleaner Plugin
 * @version: 1.0.1 (2013-12-10)
 * 
 * @uses jQuery 1.10.2
 * @uses $.ddMM 1.1.2
 *
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
$.ddMM.mm_ddHTMLCleaner = {
	//Экземпляры
	instances: new Array(),
	addInstance: function(selector, params){
		var _this = this;
		
		//Если параметры переданы, надо обработать
		if ($.isPlainObject(params)){
			//Разрешённые атрибуты для всех тегов
			if ($.type(params.validAttrsForAllTags) == 'string'){
				if ($.trim(params.validAttrsForAllTags).length > 0){
					params.validAttrsForAllTags = $.ddMM.makeArray(params.validAttrsForAllTags);
				}else{
					delete params.validAttrsForAllTags;
				}
			}
			
			//Разрешённые стили
			if ($.type(params.validStyles) == 'string'){
				if ($.trim(params.validStyles).length > 0){
					params.validStyles = $.ddMM.makeArray(params.validStyles);
				}else{
					delete params.validStyles;
				}
			}
			
			//Разрешённые атрибуты для конкретных тегов
			if ($.isPlainObject(params.validAttrs)){
				$.each(params.validAttrs, function(key, val){
					params.validAttrs[key] = $.ddMM.makeArray(val);
				});
			}else{
				delete params.validAttrs;
			}
		}
		
		_this.instances.push({
			$fields: $(selector),
			params: params
		});
		
		return _this.instances.length - 1;
	}
};

//On document.ready
$(function(){
	//Самбмит главной формы
	$.ddMM.$mutate.on('submit', function(){
		$.each($.ddMM.mm_ddHTMLCleaner.instances, function(){
			var instance = this;
			
			instance.$fields.each(function(){
				var $this = $(this);
				
				$this.val($.ddHTMLCleaner.clean($this.val(), instance.params));
			});
		});
	});
});
})(jQuery);