/**
 * jQuery ddMM.mm_ddMaxLength Plugin
 * @version: 1.0 (2013-12-10)
 * 
 * @uses jQuery 1.9.1
 * @uses $.ddTools 1.8.1
 * @uses $.ddMM 1.1.2
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
//On document.ready
$(function(){
	$.ddMM.$mutate.on('submit', function(event){
		var ddErrors = new Array();
		
		$('div.ddMaxLengthCount span').each(function(){
			var $this = $(this),
				$field = $this.parents('.ddMaxLengthCount:first').parent().find('.ddMaxLengthField');
			
			if (parseInt($this.text()) < 0){
				$field.addClass('maxLengthErrorField').focus(function(){
					$field.removeClass('maxLengthErrorField');
				});
				
				ddErrors.push($field.parents('tr').find('td:first-child .warning').text());
			}
		});
		
		if(ddErrors.length > 0){
			alert('Incorrect values for the following fields: ' + ddErrors.join(',') + '.');
			
			event.preventDefault();
		}
	});
});
})(jQuery);