/**
 * jQuery ddMM Plugin
 * @version 1.2.1 (2014-05-28)
 * 
 * @uses jQuery 1.9.1
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
$.ddMM = {
	config: {
		site_url: '',
		datetime_format: '',
		datepicker_offset: 0
	},
	urls: {
		manager: 'manager/',
		mm: 'assets/plugins/managermanager/'
	},
	/**
	 * fields {plain object} - All document fields (include tvs).
	 * fields[item] {plain object} - A field.
	 * fields[item].fieldtype {string} - Field type.
	 * fields[item].fieldname {string} - Field name.
	 * fields[item].dbname {string} - Field db name.
	 * fields[item].tv {boolean} - Is the field a tv?
	 * fields[item].$elem {jQuery} - Field jQuery element.
	 */
	fields: {},
	lang: {},
	$mutate: $(),

	/**
	 * makeArray
	 * @version 1.1 (2013-10-24)
	 * 
	 * @desctiption Makes a commas separated list into an array.
	 * 
	 * @param csv {comma separated string} - List. @required
	 * @param splitter {string} - Splitter. Default: ','.
	 * 
	 * @return {array}
	 */
	makeArray: function(csv, splitter){
		// If we've already been supplied an array, just return it
		if ($.isArray(csv)){
			return csv;
		}

		// Else if we have an empty string
		if ($.trim(csv) == ''){
			return new Array();
		}

		if ($.type(splitter) != 'string' || splitter.length == 0){
			splitter = ',';
		}

		// Otherwise, turn it into an array
		return csv.split(new RegExp('\\s*' + splitter + '\\s*'));
	},

	/**
	 * moveFields
	 * @version 1.1 (2014-05-28)
	 * 
	 * @desctiption Move a fields to some target (e.g. tab or section).
	 * 
	 * @param fields {comma separated string; array} - The name(s) of the document fields (or TVs) this should apply to. @required
	 * @param targetId {string} - The ID of the target which the fields should be moved to. @required
	 */
	moveFields: function(fields, targetId){
		var _this = this,
			$target = $('#' + targetId);

		fields = _this.makeArray(fields);

		if ($target.length > 0 && fields.length > 0){
			var ruleHtml = '<tr style="height: 10px"><td colspan="2"><div class="split"></div></td></tr>';

			$('select[id$=_prefix]').each(function(){
				$(this).parents('tr:first').addClass('urltv');
			});

			$.each(fields, function(){
				if (this == 'content'){
					//Если перемещаем в секцию
					if ($target.hasClass('sectionBody')){
						var $row = $('<tr><td valign="top"></td><td></td></tr>');

						$('#content_header').removeClass('sectionHeader').wrapInner('<span class="warning"></span>').appendTo($row.find('td:first'));

						$('#content_body').removeClass('sectionBody').appendTo($row.find('td:last'));

						$row.appendTo($target.find('> table:first')).after(ruleHtml);
					}else{
						$('#content_body').parents('tr').appendTo($target.find('> table:last'));
						//hide tv separator if there
						$('#content_body').parents('tr').prev('tr[style="height: 10px"]').hide();
					}
				//We can't move these fields because they belong in a particular place
				}else if (
					this == 'keywords' ||
					this == 'metatags' ||
					this == 'which_editor' 
					//||
					//this == 'hidemenu' ||
					//this == 'show_in_menu' ||
					//this == 'menuindex'
				){
					//Do nothing
					return;
				}else if (this == 'pub_date' || this == 'unpub_date'){
					var $helpline = $('input[name="' + this + '"]').parents('tr').next('tr').appendTo($target.find('> table:first'));

					$helpline.before($('input[name="' + this + '"]').parents('tr'));
					$helpline.after(ruleHtml);
				}else{
					if ($.isPlainObject(_this.fields[this])){
						// Identify the table row to move
						var $toMove = _this.fields[this].$elem.parents('tr:not(.urltv)');

						$toMove.find('script').remove();
						// Get rid of line after, if there is one
						$toMove.next('tr').find('td[colspan="2"]').parents('tr').remove();

						// Move the table row
						var $movedTV = $toMove.appendTo($target.find('> table:first'));

						// Insert a rule after
						$movedTV.after(ruleHtml);

						// Remove widths from label column
						//movedTV.find("td[width]").attr("width","");
						// This prevents an IE6/7 bug where the moved field would not be visible until you switched tabs
						_this.fields[this].$elem.parents('td').removeAttr('style');
					}
				}
			});
		}
	}
};

//On document.ready
$(function(){
	$.ddMM.$mutate = $('#mutate');

	//Initialization of the corresponding jQuery element for each document field
	for (var field in $.ddMM.fields){
		$.ddMM.fields[field].$elem = $('[name="' + $.ddMM.fields[field].fieldname + '"]');
	}
});
})(jQuery);
