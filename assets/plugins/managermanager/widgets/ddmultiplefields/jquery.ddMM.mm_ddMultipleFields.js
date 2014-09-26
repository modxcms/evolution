/**
 * jQuery ddMM.mm_ddMultipleFields Plugin
 * @version 1.1.1 (2014-05-15)
 * 
 * @uses jQuery 1.9.1
 * @uses $.ddTools 1.8.1
 * @uses $.ddMM 1.1.2
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
$.ddMM.mm_ddMultipleFields = {
	defaults: {
		//Разделитель строк
		splY: '||',
		//Разделитель колонок
		splX: '::',
		//Колонки
		coloumns: 'field',
		//Заголовки колонок
		coloumnsTitle: '',
		//Данные колонок
		coloumnsData: '',
		//Ширины колонок
		colWidth: '180',
		//Стиль превьюшек
		imageStyle: '',
		//Минимальное количество строк
		minRow: 0,
		//Максимальное количество строк
		maxRow: 0,
		//Конструктор поля (в случае если тип колонки == 'field')
		makeFieldFunction: 'makeNull',
		//Функция получения файлов
		browseFuntion: false
	},
//	Все экземпляры (TV). Структура: {
//		'id': {
//			currentField,
//			$addButton,
//			+Всё, что передано параметрально (см. this.defaults)
//		}
//	}
	instances: {},
	richtextWindow: null,
	//Обновляет мульти-поле, берёт значение из оригинального поля
	updateField: function(id){
		var _this = this;
		
		//Если есть текущее поле
		if (_this.instances[id].currentField){
			//Задаём значение текущему полю (берём у оригинального поля), запускаем событие изменения
			_this.instances[id].currentField.val($.trim($('#' + id).val())).trigger('change.ddEvents');
			//Забываем текущее поле (ибо уже обработали)
			_this.instances[id].currentField = false;
		}
	},
	//Обновляет оригинальное поле TV, собирая данные по мульти-полям
	updateTv: function(id){
		var _this = this,
			masRows = new Array();
		
		//Перебираем все строки
		$('#' + id + 'ddMultipleField .ddFieldBlock').each(function(){
			var $this = $(this),
				masCol = new Array(),
				id_field = {
					index: false,
					val: false,
					$field: false
				};
			
			//Перебираем все колонки, закидываем значения в массив
			$this.find('.ddField').each(function(index){
				//Если поле с типом id TODO: Какой смысл по всех этих манипуляциях?
				if (_this.instances[id].coloumns[index] == 'id'){
					id_field.index = index;
					id_field.$field = $(this);
					
					//Сохраняем значение поля
					id_field.val = id_field.$field.val();
					//Если значение пустое, то генерим
					if (id_field.val == ''){id_field.val = (new Date).getTime();}
					
					//Обнуляем значение
					id_field.$field.val('');
				}
				
				//Если колонка типа richtext
				if (_this.instances[id].coloumns[index] == 'richtext'){
					//Собираем значения строки в массив
					masCol.push($.trim($(this).html()));
				}else{
					//Собираем значения строки в массив
					masCol.push($.trim($(this).val()));
				}
			});
			
			//Склеиваем значения колонок через разделитель
			var col = masCol.join(_this.instances[id].splX);
			
			//Если значение было хоть в одной колонке из всех в этой строке
			if (col.length != ((masCol.length - 1) * _this.instances[id].splX.length)){
				//Проверяем было ли поле с id
				if (id_field.index !== false){
					//Записываем значение в поле
					id_field.$field.val(id_field.val);
					//Обновляем значение в массиве
					masCol[id_field.index] = id_field.val;
					//Пересобираем строку
					col = masCol.join(_this.instances[id].splX);
				}
				
				masRows.push(col);
			}
		});
		
		//Записываем значение в оригинальное поле
//		$('#' + id).attr('value', _this.maskQuoutes(masRows.join(_this.instances[id].splY)));
		$('#' + id).val(_this.maskQuoutes(masRows.join(_this.instances[id].splY)));
	},
	//Инициализация
	//Принимает id оригинального поля, его значения и родителя поля
	init: function(id, val, target){
		var _this = this,
			//Делаем таблицу мульти-полей, вешаем на таблицу функцию обновления оригинального поля
			$ddMultipleField = $('<table class="ddMultipleField" id="' + id + 'ddMultipleField"></table>').appendTo(target)/*.on('change.ddEvents', function(){_this.updateTv(id);})*/;
		
		//Если есть хоть один заголовок
		if (_this.instances[id].coloumnsTitle.length > 0){
			var text = '';
			
			//Создадим шапку (перебираем именно колонки!)
			$.each(_this.instances[id].coloumns, function(key, val){
				//Если это колонка с id
				if (val == 'id'){
					//Вставим пустое значение в массив с заголовками
					_this.instances[id].coloumnsTitle.splice(key, 0, '');
					
					text += '<th style="display: none;"></th>';
				}else{
					//Если такого значения нет — сделаем
					if (!_this.instances[id].coloumnsTitle[key]){
						_this.instances[id].coloumnsTitle[key] = '';
					}
					
					text += '<th>' + (_this.instances[id].coloumnsTitle[key]) + '</th>';
				}
			});
			
			$('<tr><th></th>' + text + '<th></th></tr>').appendTo($ddMultipleField);
		}
		
		//Делаем новые мульти-поля
		var arr = val.split(_this.instances[id].splY);
		
		//Проверяем на максимальное и минимальное количество строк
		if (_this.instances[id].maxRow && arr.length > _this.instances[id].maxRow){
			arr.length = _this.instances[id].maxRow;
		}else if (_this.instances[id].minRow && arr.length < _this.instances[id].minRow){
			arr.length = _this.instances[id].minRow;
		}
		
		//Создаём кнопку +
		_this.instances[id].$addButton = _this.makeAddButton(id);
		
		for (var i = 0, len = arr.length; i < len; i++){
			//В случае, если размер массива был увеличен по minRow, значением будет undefined, посему зафигачим пустую строку
			_this.makeFieldRow(id, arr[i] || '');
		}
		
		//Втыкаем кнопку + куда надо
		_this.instances[id].$addButton.appendTo($('#' + id + 'ddMultipleField .ddFieldBlock:last .ddFieldCol:last'));
		
		//Добавляем возможность перетаскивания
		$ddMultipleField.sortable({
			items: 'tr:has(td)',
			handle: '.ddSortHandle',
			cursor: 'n-resize',
			axis: 'y',
/*			tolerance: 'pointer',*/
/*			containment: 'parent',*/
			placeholder: 'ui-state-highlight',
			start: function(event, ui){
				ui.placeholder.html('<td colspan="' + (_this.instances[id].coloumns.length + 2) + '"><div></div></td>').find('div').css('height', ui.item.height());
			},
			stop: function(event, ui){
				//Находим родителя таблицы, вызываем функцию обновления поля
//				ui.item.parents('.ddMultipleField:first').trigger('change.ddEvents');
				_this.moveAddButton(id);
			}
		});
		
		//Запускаем обновление, если были ограничения
//		if (_this.instances[id].maxRow || _this.instances[id].minRow){
//			$ddMultipleField.trigger('change.ddEvents');
//		}
	},
	//Функция создания строки
	//Принимает id и данные строки
	makeFieldRow: function(id, val){
		var _this = this;
		
		//Если задано максимальное количество строк
		if (_this.instances[id].maxRow){
			//Общее количество строк на данный момент
			var fieldBlocksLen = $('#' + id + 'ddMultipleField .ddFieldBlock').length;
			
			//Проверяем превышает ли уже количество строк максимальное
			if (_this.instances[id].maxRow && fieldBlocksLen >= _this.instances[id].maxRow){
				return;
			//Если будет равно максимуму при создании этого поля
			}else if (_this.instances[id].maxRow && fieldBlocksLen + 1 == _this.instances[id].maxRow){
				_this.instances[id].$addButton.attr('disabled', true);
			}
		}
		
		var $fieldBlock = $('<tr class="ddFieldBlock ' + id + 'ddFieldBlock"><td class="ddSortHandle"><div></div></td></tr>').appendTo($('#' + id + 'ddMultipleField'));
		
		//Разбиваем переданное значение на колонки
		val = _this.maskQuoutes(val).split(_this.instances[id].splX);
		
		var $field;
		
		//Перебираем колонки
		$.each(_this.instances[id].coloumns, function(key){
			if (!val[key]){val[key] = '';}
			if (!_this.instances[id].coloumnsTitle[key]){_this.instances[id].coloumnsTitle[key] = '';}
			if (!_this.instances[id].colWidth[key] || _this.instances[id].colWidth[key] == ''){_this.instances[id].colWidth[key] = _this.instances[id].colWidth[key - 1];}
			
			var $col = _this.makeFieldCol($fieldBlock);
			
			//Если текущая колонка является полем
			if(_this.instances[id].coloumns[key] == 'field'){
				$field = _this.makeText(val[key], _this.instances[id].coloumnsTitle[key], _this.instances[id].colWidth[key], $col);
				
				_this[_this.instances[id].makeFieldFunction](id, $col);
				
				//If is file or image
				if (_this.instances[id].browseFuntion){
					//Create Attach browse button
					$('<input class="ddAttachButton" type="button" value="Вставить" />').insertAfter($field).on('click', function(){
						_this.instances[id].currentField = $(this).siblings('.ddField');
						_this.instances[id].browseFuntion(id);
					});
				}
			//Если id
			}else if (_this.instances[id].coloumns[key] == 'id'){
				$field = _this.makeText(val[key], '', 0, $col);
				
				if (!($field.val())){
					$field.val((new Date).getTime());
				}
				
				$col.hide();
			//Если селект
			}else if(_this.instances[id].coloumns[key] == 'select'){
//				$field.remove();
				_this.makeSelect(val[key], _this.instances[id].coloumnsTitle[key], _this.instances[id].coloumnsData[key], _this.instances[id].colWidth[key], $col);
			//Если дата
			}else if(_this.instances[id].coloumns[key] == 'date'){
				_this.makeDate(val[key], _this.instances[id].coloumnsTitle[key], $col);
			//Если textarea
			}else if(_this.instances[id].coloumns[key] == 'textarea'){
				_this.makeTextarea(val[key], _this.instances[id].coloumnsTitle[key], _this.instances[id].colWidth[key], $col);
			//Если richtext
			}else if(_this.instances[id].coloumns[key] == 'richtext'){
				_this.makeRichtext(val[key], _this.instances[id].coloumnsTitle[key], _this.instances[id].colWidth[key], $col);
			//По дефолту делаем текстовое поле
			}else{
				_this.makeText(val[key], _this.instances[id].coloumnsTitle[key], _this.instances[id].colWidth[key], $col);
			}
		});
		
		//Create DeleteButton
		_this.makeDeleteButton(id, _this.makeFieldCol($fieldBlock));
		
		//При изменении и загрузке
//		$('.ddField', $fieldBlock).on('load.ddEvents change.ddEvents',function(){
//			$(this).parents('.ddMultipleField:first').trigger('change.ddEvents');
//		});
		
		//Специально для полей, содержащих изображения необходимо инициализировать
		$('.ddFieldCol:has(.ddField_image) .ddField', $fieldBlock).trigger('change.ddEvents');
		
		return $fieldBlock;
	},
	//Создание колонки поля
	makeFieldCol: function($fieldRow){
		return $('<td class="ddFieldCol"></td>').appendTo($fieldRow);
	},
	//Make delete button
	makeDeleteButton: function(id, $fieldCol){
		var _this = this;
		
		$('<input class="ddDeleteButton" type="button" value="×" />').appendTo($fieldCol).on('click', function(){
			//Проверяем на минимальное количество строк
			if (_this.instances[id].minRow && $('#' + id + 'ddMultipleField .ddFieldBlock').length <= _this.instances[id].minRow){
				return;
			}
			
			var $this = $(this),
				$par = $this.parents('.ddFieldBlock:first')/*,
				$table = $this.parents('.ddMultipleField:first')*/;
			
			//Отчистим значения полей
			$par.find('.ddField').val('');
			
			//Если больше одной строки, то можно удалить текущую строчку
			if ($par.siblings('.ddFieldBlock').length > 0){
				$par.fadeOut(300, function(){
					//Если контейнер имеет кнопку добалвения, перенесём её
					if ($par.find('.ddAddButton').length > 0){
						_this.moveAddButton(id, $par.prev('.ddFieldBlock'));
					}
					
					//Сносим
					$par.remove();
					
					//При любом удалении показываем кнопку добавления
					_this.instances[id].$addButton.removeAttr('disabled');
					
					//Инициализируем событие изменения
//					$table.trigger('change.ddEvents');
					
					return;
				});
			}
			//Инициализируем событие изменения
//			$table.trigger('change.ddEvents');
		});
	},
	//Функция создания кнопки +, вызывается при инициализации
	makeAddButton: function(id){
		var _this = this;
		
		return $('<input class=\"ddAddButton\" type=\"button\" value=\"+\" />').on('click', function(){
			//Вешаем на кнопку создание новой строки
			$(this).appendTo(_this.makeFieldRow(id, '').find('.ddFieldCol:last'));
		});
	},
	//Перемещение кнопки +
	moveAddButton: function(id, $target){
		var _this = this;
		
		//Если не передали, куда вставлять, вставляем в самый конец
		if (!$target){
			$target = $('#' + id + 'ddMultipleField .ddFieldBlock:last');
		}
		
		//Находим кнопку добавления и переносим куда надо
		_this.instances[id].$addButton.appendTo($target.find('.ddFieldCol:last'));
	},
	//Make text field
	makeText: function(value, title, width, $fieldCol){
		return $('<input type="text" value="' + value + '" title="' + title + '" style="width:' + width + 'px;" class="ddField" />').appendTo($fieldCol);
	},
	//Make date field
	makeDate: function(value, title, $fieldCol){
		//name нужен для DatePicker`а
		var $field = $('<input type="text" value="' + value + '" title="' + title + '" class="ddField DatePicker" name="ddMultipleDate" />').appendTo($fieldCol);
		
		new DatePicker($field.get(0), {
			'yearOffset': $.ddMM.config.datepicker_offset,
			'format': $.ddMM.config.datetime_format + ' hh:mm:00'
		});
		
		return $field;
	},
	//Make textarea field
	makeTextarea: function(value, title, width, $fieldCol){
		return $('<textarea title="' + title + '" style="width:' + width + 'px;" class="ddField">' + value + '</textarea>').appendTo($fieldCol);
	},
	//Make richtext field
	makeRichtext: function(value, title, width, $fieldCol){
		var _this = this,
			$field = $('<div title="' + title + '" style="width:' + width + 'px;" class="ddField">' + value + '</div>').appendTo($fieldCol);
		
		$('<div class="ddFieldCol_edit"><a class="false" href="#">' + $.ddMM.lang.edit + '</a></div>').appendTo($fieldCol).find('a').on('click', function(event){
			_this.richtextWindow = window.open($.ddMM.config.site_url + $.ddMM.urls.mm + 'widgets/ddmultiplefields/richtext/index.php', 'mm_ddMultipleFields_richtext', new Array(
				'width=600',
				'height=550',
				'left=' + (($.ddTools.windowWidth - 600) / 2),
				'top=' + (($.ddTools.windowHeight - 550) / 2),
				'menubar=no',
				'toolbar=no',
				'location=no',
				'status=no',
				'resizable=no',
				'scrollbars=yes'
			).join(','));
			
			if (_this.richtextWindow != null){
				_this.richtextWindow.$ddField = $field;
			}
			
			event.preventDefault();
		});
		
		return $field;
	},
	//Make image field
	makeImage: function(id, $fieldCol){
		var _this = this;
		
		// Create a new preview and Attach a browse event to the picture, so it can trigger too
		$('<div class="ddField_image"><img src="" style="' + _this.instances[id].imageStyle + '" /></div>').appendTo($fieldCol).hide().find('img').on('click', function(){
			$fieldCol.find('.ddAttachButton').trigger('click');
		}).on('load.ddEvents', function(){
			//Удаление дерьма, блеать (превьюшка, оставленная от виджета showimagetvs)
			$('#' + id + 'PreviewContainer').remove();
		});
		
		//Находим поле, привязываем события
		$('.ddField', $fieldCol).on('change.ddEvents load.ddEvents', function(){
			var $this = $(this), url = $this.val();
			
			url = (url != '' && url.search(/http:\/\//i) == -1) ? ($.ddMM.config.site_url + url) : url;
			
			//If field not empty
			if (url != ''){
				//Show preview
				$this.siblings('.ddField_image').show().find('img').attr('src', url);
			}else{
				//Hide preview
				$this.siblings('.ddField_image').hide();
			}
		});
	},
	//Функция создания списка
	makeSelect: function(value, title, data, width, $fieldCol){
		var $select = $('<select class="ddField">');
		
		if (data){
			var dataMas = $.parseJSON(data),
				options = '';
			
			$.each(dataMas, function(index){
				options += '<option value="'+ dataMas[index][0] +'">' + (dataMas[index][1] ? dataMas[index][1] : dataMas[index][0]) +'</option>';
			});
			
			$select.append(options);
		}
		
		if (value){$select.val(value);}
		
		return $select.appendTo($fieldCol);
	},
	//Функция ничего не делает
	makeNull: function(id, $fieldCol){return false;},
	//Маскирует кавычки
	maskQuoutes: function(text){
		text = text.replace(/"/g, "&#34;");
		text = text.replace(/\'/g, "&#39;");
		
		return text;
	}
};

/**
 * jQuery.fn.mm_ddMultipleFields Plugin
 * @version 1.0.1 (2014-03-01)
 * 
 * @description Делает мультиполя.
 * 
 * Параметры передаются в виде plain object.
 * @param splY {string} - Разделитель строк. Default: '||'.
 * @param splX {string} - Разделитель колонок. Default: '::'.
 * @param coloumns {comma separated string; array} - Колонки. Default: 'field'.
 * @param coloumnsTitle {comma separated string; array} - Заголовки колонок. Default: ''.
 * @param coloumnsData {separated string; array} - Данные колонок. Default: ''.
 * @param colWidth {comma separated string} - Ширины колонок. Default: '180'.
 * @param imageStyle {string} - Стиль превьюшек. Default: ''.
 * @param minRow {integer} - Минимальное количество строк. Default: 0.
 * @param maxRow {integer} - Максимальное количество строк. Default: 0.
 * @param makeFieldFunction {string} - Имя метода конструктора поля (в случае если тип колонки == 'field'). Default: 'makeNull'.
 * @param browseFuntion {function; false} - Функция получения файлов. Default: false.
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */
$.fn.mm_ddMultipleFields = function(params){
	var _this = $.ddMM.mm_ddMultipleFields;
	
	//Обрабатываем параметры
	params = $.extend({}, _this.defaults, params || {});
	
	params.coloumns = $.ddMM.makeArray(params.coloumns);
	params.coloumnsTitle = $.ddMM.makeArray(params.coloumnsTitle);
	params.coloumnsData = $.ddMM.makeArray(params.coloumnsData, '\\|\\|');
	params.colWidth = $.ddMM.makeArray(params.colWidth);
	params.minRow = parseInt(params.minRow, 10);
	params.maxRow = parseInt(params.maxRow, 10);
	
	return $(this).each(function(){
		//Attach new load event
		$(this).on('load.ddEvents', function(event){
			//Оригинальное поле
			var $this = $(this),
				//id оригинального поля
				id = $this.attr('id');
			
			//Проверим на существование (возникали какие-то непонятные варианты, при которых два раза вызов был)
			if (!_this.instances[id]){
				//Инициализация текущего объекта с правилами
				_this.instances[id] = $.extend({}, params);
				
				//Скрываем оригинальное поле
				$this.removeClass('imageField').off('.mm_widget_showimagetvs').addClass('originalField').hide();
				
				//Назначаем обработчик события при изменении (необходимо для того, чтобы после загрузки фотки адрес вставлялся в нужное место)
				$this.on('change.ddEvents', function(){
					//Обновляем текущее мульти-поле
					_this.updateField($this.attr('id'));
				});
				
				//Если это файл или изображение, cкрываем оригинальную кнопку
				if (_this.instances[id].browseFuntion){$this.next('input[type=button]').hide();}
				
				//Создаём мульти-поле
				_this.init(id, $this.val(), $this.parent());
			}
		}).trigger('load');
	});
};

//On document.ready
$(function(){
	//If we have imageTVs on this page, modify the SetUrl function so it triggers a "change" event on the URL field
	if (typeof(SetUrl) != 'undefined'){
		//Copy the existing Image browser SetUrl function
		var oldSetUrl = SetUrl;
		
		//Redefine it to also tell the preview to update
		SetUrl = function(url, width, height, alt){
			var c;
			
			if(lastFileCtrl){
				c = $(document.mutate[lastFileCtrl]);
			}else if(lastImageCtrl){
				c = $(document.mutate[lastImageCtrl]);
			}
			
			oldSetUrl(url, width, height, alt);
			
			if (c){c.trigger('change');}
		};
	}
	
	//Самбмит главной формы
	$.ddMM.$mutate.on('submit', function(){
		$.each($.ddMM.mm_ddMultipleFields.instances, function(key){
			$.ddMM.mm_ddMultipleFields.updateTv(key);
		});
	});
});
})(jQuery);